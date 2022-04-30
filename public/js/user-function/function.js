//So sánh coi phần tử object có nằm trong phần tử của một mảng không (return True/False)
function compareInclude(obj, arr) {
    const objRaw = JSON.stringify(obj);
    for (i = 0; i < arr.length; i++) {
        let itemRaw = JSON.stringify(arr[i]);
        if (itemRaw === objRaw) {
            return true;
        }
    }

    return false;
}

export default compareInclude;