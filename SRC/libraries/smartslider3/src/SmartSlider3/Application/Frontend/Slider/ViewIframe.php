<?php


namespace Nextend\SmartSlider3\Application\Frontend\Slider;


use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\SliderManager\SliderManager;

class ViewIframe extends AbstractView {

    /** @var string|integer */
    protected $sliderIDorAlias;

    protected $sliderHTML = '';

    public function display() {

        $this->getApplicationType()
             ->enqueueAssets();

        $locale = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, "C");

        $sliderManager = new SliderManager($this, $this->sliderIDorAlias, false);
        $sliderManager->setUsage('iframe');
        $this->sliderHTML = $sliderManager->render(true);

        setlocale(LC_NUMERIC, $locale);

        echo $this->render('Iframe');
    }

    /**
     * @return string|integer
     */
    public function getSliderIDorAlias() {
        return $this->sliderIDorAlias;
    }

    /**
     * @param string|integer $sliderIDorAlias
     */
    public function setSliderIDorAlias($sliderIDorAlias) {
        $this->sliderIDorAlias = $sliderIDorAlias;
    }

    /**
     * @return string
     */
    public function getSliderHTML() {
        return $this->sliderHTML;
    }

}