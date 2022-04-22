var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var AddToCartButton = function AddToCartButton(props) {
    var _React$useState = React.useState(1),
        _React$useState2 = _slicedToArray(_React$useState, 2),
        qty = _React$useState2[0],
        setQty = _React$useState2[1];

    var uniqueId = 'productCardInputGroupSelect' + props.product.categoryId + '-' + props.product.id;
    var addToCartButtonId = 'addToCartButton' + props.product.categoryId + '-' + props.product.id;

    var addToCartHandler = function addToCartHandler() {
        productAddToCart(props.product.categoryId, props.product.id, qty, document.getElementById(addToCartButtonId));
        setQty(1);
    };

    var qtyChangeHandler = function qtyChangeHandler(ele) {
        setQty(ele.target.value);
    };

    return React.createElement(
        'div',
        { className: 'text-center p-0 mt-3' },
        React.createElement(
            'div',
            { className: 'input-group input-group-sm mt-1 flex-nowrap' },
            React.createElement(
                'select',
                { className: 'form-select pe-4', id: uniqueId, 'aria-label': 'Product Quantity', value: qty, onChange: qtyChangeHandler },
                React.createElement(
                    'option',
                    { value: '1' },
                    '1'
                ),
                React.createElement(
                    'option',
                    { value: '2' },
                    '2'
                ),
                React.createElement(
                    'option',
                    { value: '3' },
                    '3'
                ),
                React.createElement(
                    'option',
                    { value: '4' },
                    '4'
                ),
                React.createElement(
                    'option',
                    { value: '5' },
                    '5'
                )
            ),
            React.createElement(
                'button',
                { id: addToCartButtonId, className: 'btn btn-primary w-50 text-nowrap', type: 'button', onClick: addToCartHandler },
                'Add ',
                React.createElement('i', { className: 'ms-1 fa fa-shopping-cart' })
            )
        )
    );
};

var StarRating = function StarRating(props) {
    var rating = parseFloat(props.rating);
    var count = parseFloat(props.count);
    var title = 'Rated ' + rating + ' out of 5 based on ' + count + ' customer reviews';

    var getStars = function getStars() {
        return [1, 2, 3, 4, 5].map(function (i) {
            if (i <= rating) {
                starClass = 'fa fa-star';
            } else if (i == Math.ceil(rating)) {
                starClass = 'fas fa-star-half-alt';
            } else {
                starClass = 'far fa-star';
            }
            return React.createElement('i', { className: starClass + ' text-orange me-1', key: i });
        });
    };

    return React.createElement(
        React.Fragment,
        null,
        React.createElement(
            'div',
            { className: 'd-flex justify-content-start', title: title },
            getStars(),
            count > 0 ? React.createElement(
                'span',
                { className: 'text-muted small' },
                count
            ) : ''
        )
    );
};

var HomepageCategoryProducts = function HomepageCategoryProducts(props) {
    var _React$useState3 = React.useState([]),
        _React$useState4 = _slicedToArray(_React$useState3, 2),
        categoryProducts = _React$useState4[0],
        setCategoryProducts = _React$useState4[1];

    var fetchCategoryProducts = function fetchCategoryProducts() {
        fetch('/products/json').then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response && response.data && response.data.length > 0) {
                setCategoryProducts(response.data);
            }
        });
    };

    React.useEffect(function () {
        fetchCategoryProducts();
    }, []);

    var getContent = function getContent() {
        var content = '';

        if (categoryProducts.length === 0) {
            return content;
        }

        content = categoryProducts.map(function (item) {
            console.log(item.Category);

            var products = item.Category.products.map(function (product) {
                var discountValue = parseFloat(product.mrp) - parseFloat(product.salePrice);
                var discountTag = React.createElement(
                    'div',
                    { className: 'position-relative' },
                    React.createElement(
                        'div',
                        { className: 'position-absolute top-0 start-0 small' },
                        React.createElement(
                            'span',
                            { className: 'small bg-orange p-1 fw-bold border border-start-0 border-top-0 border-white' },
                            '\u20B9',
                            discountValue,
                            ' OFF'
                        )
                    )
                );

                var pricingDetails = function pricingDetails() {
                    if (parseInt(product.hideProductPrice) === 0) {
                        return React.createElement(
                            React.Fragment,
                            null,
                            React.createElement(
                                'div',
                                { className: 'mt-1 d-flex justify-content-between' },
                                React.createElement(
                                    'h6',
                                    null,
                                    React.createElement(
                                        'span',
                                        { className: 'text-danger' },
                                        '\u20B9',
                                        product.salePrice
                                    )
                                ),
                                discountValue > 0 ? React.createElement(
                                    'div',
                                    { className: 'small' },
                                    React.createElement(
                                        'span',
                                        { className: 'text-muted text-decoration-line-through small' },
                                        'MRP \u20B9',
                                        product.mrp
                                    )
                                ) : ''
                            ),
                            discountValue > 0 ? React.createElement(
                                'div',
                                { className: 'small text-center' },
                                React.createElement(
                                    'span',
                                    { className: 'text-success' },
                                    'Save \u20B9',
                                    discountValue
                                )
                            ) : '',
                            product.deliveryCharges === 0 && minOrderForFreeShipping === 0 ? React.createElement(
                                'div',
                                { className: 'small text-center' },
                                React.createElement(
                                    'span',
                                    { className: 'text-orange small' },
                                    '+ Free Delivery'
                                )
                            ) : ''
                        );
                    }
                };

                return React.createElement(
                    'div',
                    { className: 'ps-1 pe-1 py-2' },
                    React.createElement(
                        'div',
                        { id: 'productCard' + product.id, className: 'card h-100 shadow-sm p-0 mb-1 text-dark border-0', style: { width: 10 + 'rem' }, key: 'product-' + product.id },
                        discountValue > 0 ? discountTag : '',
                        React.createElement(
                            'a',
                            { href: product.productDetailsPageUrl, className: 'text-decoration-underline' },
                            React.createElement('img', { src: product.imageUrl, 'data-original': product.imageUrl, className: 'lazy w-100 img-fluid card-img-top', role: 'button', alt: product.name, id: product.imageTagId, width: '200', height: '200', loading: 'lazy' })
                        ),
                        React.createElement(
                            'div',
                            { className: 'card-body p-2 pt-3 text-left' },
                            React.createElement(
                                'a',
                                { href: product.productDetailsPageUrl, className: 'text-dark text-decoration-none small' },
                                React.createElement(
                                    'span',
                                    { style: { 'fontSize': 0.9 + 'em' } },
                                    product.name
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'mt-2 mb-3 small' },
                                React.createElement(StarRating, { rating: product.avgRating, count: product.ratingsCount })
                            ),
                            pricingDetails()
                        ),
                        React.createElement(
                            'div',
                            { className: 'card-body p-2' },
                            React.createElement(
                                'span',
                                { className: '' },
                                product.name
                            ),
                            React.createElement(
                                'span',
                                { className: '' },
                                'Rs. ',
                                product.salePrice
                            ),
                            React.createElement(
                                'div',
                                { className: '' },
                                React.createElement(
                                    'div',
                                    { className: 'mt-5' },
                                    React.createElement(
                                        'div',
                                        { className: 'position-absolute bottom-0 end-0 w-100 p-2 py-3 mt-2' },
                                        product.noStock === false ? React.createElement(AddToCartButton, { product: product }) : React.createElement(
                                            'button',
                                            { type: 'button', className: 'btn btn-sm btn-outline-secondary disabled' },
                                            'Out of stock'
                                        )
                                    )
                                )
                            )
                        )
                    )
                );
            });

            return React.createElement(
                'div',
                { key: 'category-' + item.Category.id, className: 'mb-4' },
                React.createElement(
                    'h6',
                    null,
                    item.Category.name
                ),
                React.createElement(
                    'div',
                    { className: 'table-responsive mb-3' },
                    React.createElement(
                        'div',
                        { className: 'hstack gap-2 align-items-start' },
                        products
                    )
                )
            );
        });

        return React.createElement(
            'div',
            null,
            content
        );
    };

    return React.createElement(
        React.Fragment,
        null,
        React.createElement(
            'div',
            null,
            getContent()
        )
    );
};

ReactDOM.render(React.createElement(HomepageCategoryProducts, null), document.getElementById('home_page_category_products'));