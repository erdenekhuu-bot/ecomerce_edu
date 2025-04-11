const publicPath = "/public";
const asset = (path = "") => publicPath + path;
const imagePlaceholder = (size = "square") =>
    size == "square"
        ? asset("/assets/img/placeholder.jpg")
        : asset("/assets/img/placeholder-rect.jpg");

export default {
    publicPath,
    asset,
    imagePlaceholder,
};
