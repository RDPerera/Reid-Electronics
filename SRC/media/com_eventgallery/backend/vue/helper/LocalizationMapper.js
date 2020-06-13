/**
 * Prefix for the data attribute if we want to identify it as a localization key/value pair
 * @type {string}
 */
const dataI18n = 'data-i18n-';

/**
 * Creates a class which can translate things.
 *
 * @param hmltElement
 * @returns {{t: (function(*): *)}}
 */
export let mapLocalizationData = function(hmltElement) {

    let _elements = [];

    for (var i = 0; i < hmltElement.attributes.length; i++) {
        var attrib = hmltElement.attributes[i];
        if (attrib.name.indexOf(dataI18n) === 0) {
            _elements[attrib.name.replace(dataI18n,'').toUpperCase()] = attrib.value;
        }
    }

    return {
        /**
         * Performs a translation using the given localization key
         *
         * @param localizationKey
         * @returns {*}
         */
        t: (localizationKey) => {
            let lookup = localizationKey.toUpperCase();
            return _elements[lookup] ? _elements[lookup] : localizationKey;
        }
    };
};

