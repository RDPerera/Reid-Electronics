<?php


namespace Nextend\SmartSlider3\Renderable\Item\Vimeo;


use Nextend\Framework\Image\Image;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Renderable\Item\AbstractItemFrontend;
use Nextend\SmartSlider3\Settings;

class ItemVimeoFrontend extends AbstractItemFrontend {

    public function render() {
        $owner = $this->layer->getOwner();

        $url = $owner->fill($this->data->get("vimeourl"));
        if (preg_match('/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $url, $matches)) {
            $videoID = $matches[3];
        } else {
            $videoID = preg_replace('/\D/', '', $url);
        }

        $this->data->set("vimeocode", $videoID);

        $hasImage = 0;
        $image    = $this->data->get('image');

        $coverImage = '';
        if (!empty($image)) {
            $style     = 'cursor:pointer; background: URL(' . ResourceTranslator::toUrl($image) . ') no-repeat 50% 50%; background-size: cover';
            $hasImage  = 1;
            $playImage = '';

            if ($this->data->get('playbutton', 1) == 1) {

                $playWidth  = intval($this->data->get('playbuttonwidth', '48'));
                $playHeight = intval($this->data->get('playbuttonheight', '48'));
                if ($playWidth > 0 && $playHeight > 0) {

                    $attributes = array(
                        'style' => ''
                    );

                    $attributes['style'] .= 'width:' . $playWidth . 'px;';
                    $attributes['style'] .= 'height:' . $playHeight . 'px;';
                    $attributes['style'] .= 'margin-left:' . ($playWidth / -2) . 'px;';
                    $attributes['style'] .= 'margin-top:' . ($playHeight / -2) . 'px;';

                    $playButtonImage = $this->data->get('playbuttonimage', '');
                    if (!empty($playButtonImage)) {
                        $src = ResourceTranslator::toUrl($this->data->get('playbuttonimage', ''));
                    } else {
                        $src = Image::SVGToBase64('$ss3-frontend$/images/play.svg');
                    }

                    $playImage = Html::image($src, 'Play', $attributes);
                }
            }

            $coverImage = Html::tag('div', array(
                'class' => 'n2_ss_video_player__cover',
                'style' => $style
            ), $playImage);
        }

        $this->data->set('privacy-enhanced', intval(Settings::get('youtube-privacy-enhanced', 0)));

        $owner->addScript('new N2Classes.FrontendItemVimeo(this, "' . $this->id . '", "' . $owner->getElementID() . '", ' . $this->data->toJSON() . ', ' . $hasImage . ', ' . $owner->fill($this->data->get('start', '0')) . ');');

        return Html::tag('div', array(
            'id'                => $this->id,
            'class'             => 'n2_ss_video_player n2-ss-item-content n2-ow-all',
            'data-aspect-ratio' => $this->data->get('aspect-ratio', '16:9')
        ), '<div class="n2_ss_video_player__placeholder"></div>' . $coverImage);
    }

    public function renderAdminTemplate() {

        return Html::tag('div', array(
            "class"             => 'n2_ss_video_player n2-ow-all',
            'data-aspect-ratio' => $this->data->get('aspect-ratio', '16:9'),
            "style"             => 'background: URL(' . ResourceTranslator::toUrl($this->data->getIfEmpty('image', '$ss3-frontend$/images/placeholder/video.png')) . ') no-repeat 50% 50%; background-size: cover;'
        ), '<div class="n2_ss_video_player__placeholder"></div>' . '<div class="n2_ss_video_player__cover">' . Html::image(Image::SVGToBase64('$ss3-frontend$/images/play.svg')) . '</div>');

    }

    public function needWidth() {
        return true;
    }
}