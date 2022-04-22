const categoriesMenuContainerId = 'categories_menu_container';
const homePageCategoriesMenuContainerId = 'homepage_categories_menu_container';

const CategoryContext = React.createContext({
    items: [],
    fetchItems: () => { }
});

const CategoryContextProvider = (props) => {
    const [items, setItems] = React.useState([]);
    const fetchItems = () => {
        fetch('/categories/menu_json')
            .then((response) => response.json())
            .then((response) => {
                if (response.data && response.data.length > 0) {
                    clearHomePageDivContent();
                    setItems(response.data);
                }
            });
    }

    const clearHomePageDivContent = () => {
        if (document.getElementById(homePageCategoriesMenuContainerId)) {
            document.getElementById(homePageCategoriesMenuContainerId).innerHTML = '';
        }
    };

    React.useEffect(() => {
        fetchItems();
    }, []);

    return (
        <CategoryContext.Provider value={{
            items: items,
            fetchItems: fetchItems,
        }}>{props.children}</CategoryContext.Provider>
    );
}

const CategoriesMenu = () => {
    const ctx = React.useContext(CategoryContext);

    const menuItems = () => {
        let liContent = [];

        if (ctx.items.length > 0) {
            liContent = ctx.items.map((item) => {
                return (
                    <li className="list-group-item px-0 py-1" key={item.id}>
                        <a
                            className="nav-link d-flex justify-content-between"
                            href={item.productsUrl}
                            title={item.name}
                        >
                            <span>{item.name} ({item.productsCount})</span>
                            <span>
                                <i className="fa fa-chevron-right"></i>
                            </span>
                        </a>
                    </li>
                );
            })

            liContent.push(<li className="list-group-item px-0 pt-2" key="category-nav-menu-showAllProducts">
                <a className="nav-link" href="/products/showAll" title="Show all products">
                    <i className="fa fa-chevron-circle-right"></i> Show All Products
                </a>
            </li>);
        } else {
            liContent.push(<li className="list-group-item px-0 pt-2" key="category-nav-menu-noProducts">
                No categories found
            </li>);
        }

        return <ul className="list-group list-group-flush">{liContent}</ul>;
    };

    return (<React.Fragment>{menuItems()}</React.Fragment>);
}

const HomepageCategoriesMenu = () => {
    const ctx = React.useContext(CategoryContext);

    const shareButtonClickHandler = (e) => {
        e.preventDefault();
        shareThis(e.target.dataset.title, e.target.dataset.text, e.target.dataset.url, e.target.dataset.files);
    }

    const menuItems = () => {
        let content = [];

        if (ctx.items.length > 0) {
            const filteredItems = ctx.items.filter((element) => { return element.imageUrl.trim().length > 0 });

            content = filteredItems.map((item, index) => {
                return (
                    <div id={'categoryCard' + item.id} key={'categoryCard' + item.id}>
                        <div className="text-center" id={'category' + item.id} style={{ width: 9 + 'rem' }}>
                            <a href={item.productsUrl} className="text-decoration-none d-block">
                                <img
                                    src={item.imageUrl}
                                    className="img-fluid rounded-circle"
                                    role="button"
                                    alt={item.name}
                                    width="300"
                                    height="300"
                                    loading={index > 2 ? 'lazy' : 'eager'}
                                />
                            </a>

                            <div className="card-body">
                                <a href={item.productsUrl} className="text-decoration-none">
                                    <h6 className="small">{item.name}</h6>
                                </a>
                                <div className="small">
                                    <a href={item.productsUrl} className="text-decoration-none">
                                        {item.productsCount} {item.productsCount === 0 ? 'No items' : (item.productsCount === 1 ? 'item' : 'items')}
                                    </a>
                                    <a href="#" title="Share" role="button" className="shareButton ms-2 d-none">
                                        <i
                                            className="fa fa-share-nodes"
                                            onClick={shareButtonClickHandler}
                                            data-title={item.name}
                                            data-text={''}
                                            data-url={item.productsUrl}
                                            data-files={''}></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            });

            return (<div className="table-responsive mb-3">
                <div className="hstack gap-3 align-items-start">
                    {content}
                </div>
            </div>
            );
        }

        return content;
    };

    return document.getElementById(homePageCategoriesMenuContainerId) 
    ? ReactDOM.createPortal(<React.Fragment>{menuItems()}</React.Fragment>, document.getElementById(homePageCategoriesMenuContainerId))
    : <React.Fragment></React.Fragment>;
}

if (document.getElementById(categoriesMenuContainerId)) {
    ReactDOM.render(<CategoryContextProvider><CategoriesMenu /><HomepageCategoriesMenu /></CategoryContextProvider>, document.getElementById(categoriesMenuContainerId));
}