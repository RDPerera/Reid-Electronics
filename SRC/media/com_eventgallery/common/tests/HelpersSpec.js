import {removeElement, setCSSStyle} from "../js/Helpers";

describe("Testing the removeElement function", function() {

    it("Removing a element works", function() {
        let array = [1,2,3,4,5];
        removeElement(array,4);
        expect(array.length).toBe(4);
        expect(array).toEqual([1,2,3,5]);
    });

    it("Removing a element from an empty array works", function() {
        let array = [];
        removeElement(array,4);
        expect(array.length).toBe(0);
        expect(array).toEqual([]);
    });

    it("Removing a element which does not exist in the array does not cause any harm", function() {
        let array = [1,2,3];
        removeElement(array,4);
        expect(array.length).toBe(3);
        expect(array).toEqual([1,2,3]);
    });

});

describe("Testing the setCSSStyle function", function() {

    let elements;

    beforeEach(function () {
        let html = `
                    <ul>
                        <li class="foo1"></li>
                        <li class="foo1"></li>
                        <li class="foo1"></li>
                    </ul>`;
        document.body.innerHTML = html;
        elements = document.getElementsByClassName('foo1');
    });

    it("Set style for elements", function() {
        setCSSStyle(elements, 'border-width', '10px');
        for(let i=0; i<elements.length; i++) {
            expect(elements[i].style.borderWidth).toBe('10px');
        }
    });
});