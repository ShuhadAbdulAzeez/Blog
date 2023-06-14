<div class="col-lg-3 mb-4">
    <div class="card" style="width: 18rem;">
        <img src="<?=get_image($row['image'])?>" class="card-img-top fixed-size-image" alt="Card image">
        <div class="card-body">
            <strong class="d-inline-block mb-2 text-primary"><?=esc($row['category'] ?? 'Unknown')?></strong>
            <h5 class="card-title"><?=esc($row['title'])?></h5>
            <div class="mb-1 text-muted"><?= date("jS M, Y", strtotime($row['date'])) ?></div>
            <p class="card-text">
                <?=esc(substr($row['content'], 0, 25))?>
            </p>
            <a href="<?=ROOT?>/post/<?=$row['slug']?>" class="btn btn-primary">Read More</a>
        </div>
    </div>
</div>

<style>
    .fixed-size-image {
        width: 100%; /* Set the desired width */
        height: 200px; /* Set the desired height */
        object-fit: cover; /* Maintain aspect ratio and crop if necessary */
    }
</style>
