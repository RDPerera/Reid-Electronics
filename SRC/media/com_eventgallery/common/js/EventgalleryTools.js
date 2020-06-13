import { addUrlHashParameter,
    calcBorderWidth,
    mergeObjects,
    addUrlParameter,
    getUrlHashParameterValue,
    removeUrlHashParameter,
    removeUrlParameter,
    setCSSStyle
} from './Helpers';

window.Eventgallery = window.Eventgallery || {};

Eventgallery.jQuery = eventgallery.jQuery;

Eventgallery.Tools = {};
Eventgallery.Tools.mergeObjects = mergeObjects;
Eventgallery.Tools.calcBorderWidth = calcBorderWidth;
Eventgallery.Tools.addUrlHashParameter = addUrlHashParameter;
Eventgallery.Tools.getUrlHashParameterValue = getUrlHashParameterValue;
Eventgallery.Tools.removeUrlHashParameter = removeUrlHashParameter;
Eventgallery.Tools.addUrlParameter = addUrlParameter;
Eventgallery.Tools.removeUrlParameter = removeUrlParameter;
Eventgallery.Tools.setCSSStyle = setCSSStyle;
