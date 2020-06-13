export function createBatches(items, max_items_per_batch) {

    let batches = [],
        currentBatch = [],
        i;

    for(i = 0; i < items.length; i++) {
        if (currentBatch.length === max_items_per_batch) {
            batches.push(currentBatch);
            currentBatch = [];
        }

        currentBatch.push(items[i]);
    }

    batches.push(currentBatch);

    return batches;
}