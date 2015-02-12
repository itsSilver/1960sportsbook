<div id="bottom-images">    
    <?php
    $images = $this->requestAction('bottom_images/getImages/');
    if (!empty($images)) {
        foreach ($images as $image):
            $url = $this->MyHtml->customUrl($image['BottomImage']['url']);
            echo $this->Html->image('bottom' . DS . $image['BottomImage']['image'], array(
                'url' => $url,
                'alt' => $image['BottomImage']['name']
                    )
            );
        endforeach;
    }
    ?>
</div>