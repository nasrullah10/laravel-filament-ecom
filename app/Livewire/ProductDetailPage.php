<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductDetailPage extends Component
{
    use WithFileUploads, WithPagination;

    public Product $product;
    public $slug;
    public $quantity = 1;
    public $relatedProducts = [];
    public $reviewRating = 5;
    public $reviewTitle = '';
    public $reviewComment = '';
    public $reviewImages = [];
    public $reviewVideo;
    public $reviewMediaType = 'image';
    public $reviewFeedback = '';
    public $reviewFeedbackType = '';
    public $reviewSort = 'newest';
    public $reviewRatingFilter = 'all';
    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with('brand')->where('slug', $slug)->firstOrFail();
        
        // Fetch related products (same category, exclude current)
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', 1)
            ->limit(4)
            ->get();
    }

    public function increaseQuantity()
    {
        $this->quantity++;
        $this->dispatch('refresh');
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->dispatch('refresh');
        }
    }

    public function selectReviewMedia(string $type): void
    {
        if (! in_array($type, ['image', 'video'], true)) {
            return;
        }

        $this->reviewMediaType = $type;

        if ($type === 'image') {
            $this->reviewVideo = null;
        } else {
            $this->reviewImages = [];
        }

        $this->resetValidation(['reviewImages', 'reviewImages.*', 'reviewVideo']);
    }

    public function addToCart($product_id)
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);
        
        // 👇 Sirf dispatch, ->to() nahi chahiye
        $this->dispatch('update-to-cart', total_count: $total_count);
        
        LivewireAlert::title('Added to cart!')
            ->success()
            ->show();
    }

    public function buyNow($product_id)
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()
            ->whereKey($product_id)
            ->where('is_active', true)
            ->firstOrFail();

        if (! $product->in_stock) {
            LivewireAlert::title('This product is currently out of stock.')
                ->warning()
                ->show();

            return;
        }

        CartManagement::addItemToCartWithQuantity($product->id, $this->quantity);

        return redirect()->route('checkout');
    }

    public function submitReview(): void
    {
        $this->reviewFeedback = '';
        $this->reviewFeedbackType = '';
        $this->resetErrorBag();

        if (! auth()->check()) {
            $this->redirectRoute('login');
            return;
        }

        $deliveredItem = OrderItem::query()
            ->where('product_id', $this->product->id)
            ->whereHas('order', fn ($query) => $query
                ->where('user_id', auth()->id())
                ->where('status', 'delivered'))
            ->latest('id')
            ->first();

        if (! $deliveredItem) {
            $this->setReviewFeedback('Only customers with a delivered order can review this product.', 'error');
            return;
        }

        if (ProductReview::where('product_id', $this->product->id)->where('user_id', auth()->id())->exists()) {
            $this->setReviewFeedback('You have already submitted a review for this product.', 'error');
            return;
        }

        $validated = $this->validate([
            'reviewRating' => ['required', 'integer', 'between:1,5'],
            'reviewTitle' => ['nullable', 'string', 'max:120'],
            'reviewComment' => ['required', 'string', 'min:10', 'max:2000'],
            'reviewImages' => ['array', 'max:3'],
            'reviewImages.*' => ['image', 'max:3072'],
            'reviewVideo' => ['required_if:reviewMediaType,video', 'nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:25600'],
            'reviewMediaType' => ['required', 'in:image,video'],
        ], [
            'reviewComment.required' => 'Please write your review.',
            'reviewComment.min' => 'Your review must be at least 10 characters.',
            'reviewImages.max' => 'You can upload a maximum of 3 images.',
            'reviewImages.*.max' => 'Each image must not be larger than 3MB.',
            'reviewVideo.required_if' => 'Please select a video before submitting.',
            'reviewVideo.mimetypes' => 'The video must be an MP4, WebM or MOV file.',
            'reviewVideo.max' => 'The video must not be larger than 25MB.',
        ]);

        try {
            $images = collect($this->reviewImages ?? [])
                ->map(fn ($image) => $image->store('reviews', 'public'))
                ->all();
            $videos = $this->reviewVideo
                ? [$this->reviewVideo->store('reviews/videos', 'public')]
                : [];

            ProductReview::create([
                'product_id' => $this->product->id,
                'user_id' => auth()->id(),
                'order_id' => $deliveredItem->order_id,
                'rating' => $validated['reviewRating'],
                'title' => $validated['reviewTitle'] ?: null,
                'comment' => $validated['reviewComment'],
                'images' => $images ?: null,
                'videos' => $videos ?: null,
                'status' => 'pending',
                'is_verified_purchase' => true,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Product review submission failed.', [
                'product_id' => $this->product->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);
            $this->setReviewFeedback('We could not submit your review. Please try again.', 'error');
            return;
        }

        $this->reset('reviewTitle', 'reviewComment', 'reviewImages', 'reviewVideo');
        $this->reviewRating = 5;
        $this->reviewMediaType = 'image';
        $this->resetValidation();

        $mediaMessage = count($videos) > 0 ? ' Your video was uploaded successfully.' : (count($images) > 0 ? ' Your images were uploaded successfully.' : '');
        $this->setReviewFeedback('Thank you! Your review was submitted and is pending admin approval.'.$mediaMessage, 'success');
    }

    private function setReviewFeedback(string $message, string $type): void
    {
        $this->reviewFeedback = $message;
        $this->reviewFeedbackType = $type;
    }

    public function updatedReviewSort(): void
    {
        $this->resetPage();
    }

    public function filterReviewsByRating(string $rating): void
    {
        if (! in_array($rating, ['all', '1', '2', '3', '4', '5'], true)) {
            return;
        }

        $this->reviewRatingFilter = $rating;
        $this->resetPage();
    }

    public function render()
    {
        $product = Product::with('brand')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('slug', $this->slug)
            ->firstOrFail();
        $approvedReviewsQuery = $product->approvedReviews();
        $reviewCount = (clone $approvedReviewsQuery)->count();
        $averageRating = $reviewCount > 0
            ? (float) (clone $approvedReviewsQuery)->avg('rating')
            : 0.0;
        $ratingCounts = (clone $approvedReviewsQuery)
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');
        $ratingBreakdown = collect(range(5, 1))->mapWithKeys(fn ($rating) => [
            $rating => (int) ($ratingCounts[$rating] ?? 0),
        ])->all();

        $reviewsQuery = $product->approvedReviews()->with('user');
        if ($this->reviewRatingFilter !== 'all') {
            $reviewsQuery->where('rating', (int) $this->reviewRatingFilter);
        }
        match ($this->reviewSort) {
            'highest' => $reviewsQuery->orderByDesc('rating')->latest('approved_at'),
            'lowest' => $reviewsQuery->orderBy('rating')->latest('approved_at'),
            default => $reviewsQuery->latest('approved_at'),
        };
        $reviews = $reviewsQuery->paginate(5);
        $canReview = auth()->check()
            && ! ProductReview::where('product_id', $product->id)->where('user_id', auth()->id())->exists()
            && OrderItem::where('product_id', $product->id)->whereHas('order', fn ($query) => $query
                ->where('user_id', auth()->id())->where('status', 'delivered'))->exists();

        return view('livewire.product-detail-page', [
            'product' => $product,
            'reviews' => $reviews,
            'canReview' => $canReview,
            'reviewCount' => $reviewCount,
            'averageRating' => $averageRating,
            'ratingBreakdown' => $ratingBreakdown,
            'filteredReviewCount' => $reviews->total(),
        ])->title($product->name.' - NAAS Shopping');
    }
}
