import {mapLocalizationData} from "../vue/helper/LocalizationMapper";

describe("Testing the localization mapping", function() {

    let element;

    beforeEach(function () {
        let html = `
                    <div id="e" data-i18n-foobar="foobar translated">                        
                    </div>`;
        document.body.innerHTML = html;
        element = document.getElementById('e');
    });

    it("Removing a element works", function() {
      let i18n = mapLocalizationData(e);
      expect(i18n.t('foobar')).toBe('foobar translated');
      expect(i18n.t('foobar2')).toBe('foobar2');

    });


});