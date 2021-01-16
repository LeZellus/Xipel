document.addEventListener("DOMContentLoaded", function() {
    let label = document.getElementById("labelCategory");
    let color = document.getElementById("colorCategory");
    let categoryPreview = document.getElementById("categoryPreview");
    let categoryPreviewLabel = document.getElementById("categoryPreviewLabel");
    let categoryPreviewButton = document.getElementById("categoryPreviewButton");

    function previewRender() {
        let colorValue = color.value;
        let labelValue = label.value;

        categoryPreviewLabel.innerHTML = labelValue;
        categoryPreview.style.background = colorValue;
    }

    document.querySelector('#categoryPreviewButton').addEventListener("click", ()=>previewRender());
});