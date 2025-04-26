<style>
    /* Add these styles to the existing CSS */
    .recipe-media-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto 20px;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .recipe-media-container img,
    .recipe-media-container video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .recipe-media-container video {
        display: none;
    }
    
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        transition: all 0.3s ease;
        z-index: 10;
        cursor: pointer;
    }
    
    .play-button:hover {
        background-color: rgba(0, 0, 0, 0.9);
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    .play-icon {
        font-size: 40px;
        margin-left: 5px; /* Slight offset to center the play icon visually */
    }
</style>

<div class="recipe-media-container">
    @if($recipe->video_url)
        <video controls preload="metadata" style="display: none; width: 100%;">
            <source src="{{ $recipe->video_url }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
        <div class="play-button" onclick="playVideo(this)">
            <span class="play-icon">▶</span>
        </div>
    @elseif($recipe->image_url)
        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
    @else
        <div class="recipe-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 400px;">
            <span>No Media</span>
        </div>
    @endif
</div>

<script>
function playVideo(playButton) {
    const container = playButton.parentElement;
    const video = container.querySelector('video');
    const image = container.querySelector('.recipe-image');
    
    if (video) {
        // Show and play this video
        video.style.display = 'block';
        image.style.display = 'none';
        playButton.style.display = 'none';
        video.play();
        
        // Add event listener to show play button again when video ends
        video.onended = function() {
            video.style.display = 'none';
            image.style.display = 'block';
            playButton.style.display = 'flex';
        };
    }
}
</script> 