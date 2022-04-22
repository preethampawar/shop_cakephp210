var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var categoriesMenuContainerId = 'categories_menu_container';
var homePageCategoriesMenuContainerId = 'homepage_categories_menu_container';

var CategoryContext = React.createContext({
    items: [],
    fetchItems: function fetchItems() {}
});

var CategoryContextProvider = function CategoryContextProvider(props) {
    var _React$useState = React.useState([]),
        _React$useState2 = _slicedToArray(_React$useState, 2),
        items = _React$useState2[0],
        setItems = _React$useState2[1];

    var fetchItems = function fetchItems() {
        fetch('/categories/menu_json').then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.data && response.data.length > 0) {
                clearHomePageDivContent();
                setItems(response.data);
            }
        });
    };

    var clearHomePageDivContent = function clearHomePageDivContent() {
        if (document.getElementById(homePageCategoriesMenuContainerId)) {
            document.getElementById(homePageCategoriesMenuContainerId).innerHTML = '';
        }
    };

    React.useEffect(function () {
        fetchItems();
    }, []);

    return React.createElement(
        CategoryContext.Provider,
        { value: {
                items: items,
                fetchItems: fetchItems
            } },
        props.children
    );
};

var CategoriesMenu = function CategoriesMenu() {
    var ctx = React.useContext(CategoryContext);

    var menuItems = function menuItems() {
        var liContent = [];

        if (ctx.items.length > 0) {
            liContent = ctx.items.map(function (item) {
                return React.createElement(
                    'li',
                    { className: 'list-group-item px-0 py-1', key: item.id },
                    React.createElement(
                        'a',
                        {
                            className: 'nav-link d-flex justify-content-between',
                            href: item.productsUrl,
                            title: item.name
                        },
                        React.createElement(
                            'span',
                            null,
                            item.name,
                            ' (',
                            item.productsCount,
                            ')'
                        ),
                        React.createElement(
                            'span',
                            null,
                            React.createElement('i', { className: 'fa fa-chevron-right' })
                        )
                    )
                );
            });

            liContent.push(React.createElement(
                'li',
                { className: 'list-group-item px-0 pt-2', key: 'category-nav-menu-showAllProducts' },
                React.createElement(
                    'a',
                    { className: 'nav-link', href: '/products/showAll', title: 'Show all products' },
                    React.createElement('i', { className: 'fa fa-chevron-circle-right' }),
                    ' Show All Products'
                )
            ));
        } else {
            liContent.push(React.createElement(
                'li',
                { className: 'list-group-item px-0 pt-2', key: 'category-nav-menu-noProducts' },
                'No categories found'
            ));
        }

        return React.createElement(
            'ul',
            { className: 'list-group list-group-flush' },
            liContent
        );
    };

    return React.createElement(
        React.Fragment,
        null,
        menuItems()
    );
};

var HomepageCategoriesMenu = function HomepageCategoriesMenu() {
    var ctx = React.useContext(CategoryContext);

    var shareButtonClickHandler = function shareButtonClickHandler(e) {
        e.preventDefault();
        shareThis(e.target.dataset.title, e.target.dataset.text, e.target.dataset.url, e.target.dataset.files);
    };

    var menuItems = function menuItems() {
        var content = [];

        if (ctx.items.length > 0) {
            var filteredItems = ctx.items.filter(function (element) {
                return element.imageUrl.trim().length > 0;
            });

            content = filteredItems.map(function (item, index) {
                return React.createElement(
                    'div',
                    { id: 'categoryCard' + item.id, key: 'categoryCard' + item.id },
                    React.createElement(
                        'div',
                        { className: 'text-center', id: 'category' + item.id, style: { width: 9 + 'rem' } },
                        React.createElement(
                            'a',
                            { href: item.productsUrl, className: 'text-decoration-none d-block' },
                            React.createElement('img', {
                                src: item.imageUrl,
                                className: 'img-fluid rounded-circle',
                                role: 'button',
                                alt: item.name,
                                width: '300',
                                height: '300',
                                loading: index > 2 ? 'lazy' : 'eager'
                            })
                        ),
                        React.createElement(
                            'div',
                            { className: 'card-body' },
                            React.createElement(
                                'a',
                                { href: item.productsUrl, className: 'text-decoration-none' },
                                React.createElement(
                                    'h6',
                                    { className: 'small' },
                                    item.name
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'small' },
                                React.createElement(
                                    'a',
                                    { href: item.productsUrl, className: 'text-decoration-none' },
                                    item.productsCount,
                                    ' ',
                                    item.productsCount === 0 ? 'No items' : item.productsCount === 1 ? 'item' : 'items'
                                ),
                                React.createElement(
                                    'a',
                                    { href: '#', title: 'Share', role: 'button', className: 'shareButton ms-2 d-none' },
                                    React.createElement('i', {
                                        className: 'fa fa-share-nodes',
                                        onClick: shareButtonClickHandler,
                                        'data-title': item.name,
                                        'data-text': '',
                                        'data-url': item.productsUrl,
                                        'data-files': '' })
                                )
                            )
                        )
                    )
                );
            });

            return React.createElement(
                'div',
                { className: 'table-responsive mb-3' },
                React.createElement(
                    'div',
                    { className: 'hstack gap-3 align-items-start' },
                    content
                )
            );
        }

        return content;
    };

    return document.getElementById(homePageCategoriesMenuContainerId) ? ReactDOM.createPortal(React.createElement(
        React.Fragment,
        null,
        menuItems()
    ), document.getElementById(homePageCategoriesMenuContainerId)) : React.createElement(React.Fragment, null);
};

if (document.getElementById(categoriesMenuContainerId)) {
    ReactDOM.render(React.createElement(
        CategoryContextProvider,
        null,
        React.createElement(CategoriesMenu, null),
        React.createElement(HomepageCategoriesMenu, null)
    ), document.getElementById(categoriesMenuContainerId));
}