import { createBatches } from "../js/BatchCreator";

describe("Testing the batch creation", function() {

    it("Splitting an array into batches works", function() {
        let array = [1,2,3,4,5];
        let batches = createBatches(array, 2);
        expect(batches.length).toBe(3);
        expect(batches[2]).toEqual([5]);
    });

    it("Splitting an array if too small for multiple batches works", function() {
        let array = [1,2,3,4,5];
        let batches = createBatches(array, 10);
        expect(batches.length).toBe(1);
        expect(batches[0]).toEqual(array);
    });

    it("Splitting an array empty array works", function() {
        let array = [];
        let batches = createBatches(array, 2);
        expect(batches.length).toBe(1);
        expect(batches[0]).toEqual(array);
    });
});