const AddToCartButton = (props) => {
    const [qty, setQty] = React.useState(1);

    const uniqueId = 'productCardInputGroupSelect' + props.product.categoryId + '-' + props.product.id;
    const addToCartButtonId = 'addToCartButton' + props.product.categoryId + '-' + props.product.id;

    const addToCartHandler = () => {
        productAddToCart(props.product.categoryId, props.product.id, qty, document.getElementById(addToCartButtonId));
        setQty(1);
    }

    const qtyChangeHandler = (ele) => {
        setQty(ele.target.value);
    }

    return (
        <div className="text-center p-0 mt-3">
            <div className="input-group input-group-sm mt-1 flex-nowrap">
                <select className="form-select pe-4" id={uniqueId} aria-label="Product Quantity" value={qty} onChange={qtyChangeHandler}>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <button id={addToCartButtonId} className="btn btn-primary w-50 text-nowrap" type="button" onClick={addToCartHandler}>
                    Add <i className="ms-1 fa fa-shopping-cart"></i>
                </button>
            </div>
        </div>
    );
}

const StarRating = (props) => {
    const rating = parseFloat(props.rating);
    const count = parseFloat(props.count);
    const title = 'Rated ' + rating + ' out of 5 based on ' + count + ' customer reviews';



    const getStars = () => {
        return [1, 2, 3, 4, 5].map((i) => {
            if (i <= rating) {
                starClass = 'fa fa-star';
            } else if (i == Math.ceil(rating)) {
                starClass = 'fas fa-star-half-alt';
            } else {
                starClass = 'far fa-star';
            }
            return <i className={starClass + ' text-orange me-1'} key={i}></i>
        });
    }



    return (<React.Fragment>
        <div className="d-flex justify-content-start" title={title}>
            {getStars()}
            {count > 0 ? <span className="text-muted small">{count}</span> : ''}
        </div>
    </React.Fragment>);
}

const HomepageCategoryProducts = (props) => {
    const [categoryProducts, setCategoryProducts] = React.useState([]);

    const fetchCategoryProducts = () => {
        fetch('/products/json')
            .then((response) => response.json())
            .then((response) => {
                if (response && response.data && response.data.length > 0) {
                    setCategoryProducts(response.data);
                }
            });
    }

    React.useEffect(() => {
        fetchCategoryProducts();
    }, []);

    const getContent = () => {
        let content = '';

        if (categoryProducts.length === 0) {
            return content;
        }

        content = categoryProducts.map((item) => {
            console.log(item.Category);


            const products = item.Category.products.map((product) => {
                const discountValue = (parseFloat(product.mrp) - parseFloat(product.salePrice));
                const discountTag = (<div className="position-relative">
                    <div className="position-absolute top-0 start-0 small">
                        <span className="small bg-orange p-1 fw-bold border border-start-0 border-top-0 border-white">
                            &#8377;{discountValue} OFF
                        </span>
                    </div>
                </div>);

                const pricingDetails = () => {
                    if (parseInt(product.hideProductPrice) === 0) {
                        return (
                            <React.Fragment>
                                <div className="mt-1 d-flex justify-content-between">
                                    <h6>
                                        <span className="text-danger">&#8377;{product.salePrice}</span>
                                    </h6>

                                    {
                                        discountValue > 0
                                            ? (<div className="small">
                                                <span className="text-muted text-decoration-line-through small">MRP &#8377;{product.mrp}</span>
                                            </div>)
                                            : ''
                                    }
                                </div>

                                {
                                    discountValue > 0
                                        ? (<div className="small text-center">
                                            <span className="text-success">Save &#8377;{discountValue}</span>
                                        </div>)
                                        : ''
                                }

                                {
                                    product.deliveryCharges === 0 && minOrderForFreeShipping === 0
                                        ? (<div className="small text-center">
                                            <span className="text-orange small">+ Free Delivery</span>
                                        </div>)
                                        : ''
                                }


                            </React.Fragment>
                        )
                    }
                }

                return (
                    <div className="ps-1 pe-1 py-2">
                        <div id={'productCard' + product.id} className="card h-100 shadow-sm p-0 mb-1 text-dark border-0" style={{ width: 10 + 'rem' }} key={'product-' + product.id}>

                            {discountValue > 0 ? discountTag : ''}

                            <a href={product.productDetailsPageUrl} className="text-decoration-underline">
                                <img src={product.imageUrl} data-original={product.imageUrl} className="lazy w-100 img-fluid card-img-top" role="button" alt={product.name} id={product.imageTagId} width="200" height="200" loading="lazy" />
                            </a>

                            <div className="card-body p-2 pt-3 text-left">
                                <a href={product.productDetailsPageUrl} className="text-dark text-decoration-none small">
                                    <span style={{ 'fontSize': 0.9 + 'em' }}>{product.name}</span>
                                </a>

                                <div className="mt-2 mb-3 small">
                                    <StarRating rating={product.avgRating} count={product.ratingsCount}></StarRating>
                                </div>

                                {pricingDetails()}



                            </div>



                            {/* <img src={product.imageUrl} className="card-img-top" style={{ height: 126 + 'px' }} /> */}
                            <div className="card-body p-2">
                                <span className="">{product.name}</span>
                                <span className="">Rs. {product.salePrice}</span>
                                <div className="">
                                    <div className="mt-5">
                                        <div className="position-absolute bottom-0 end-0 w-100 p-2 py-3 mt-2">
                                            {
                                                product.noStock === false
                                                    ? <AddToCartButton product={product} />
                                                    : <button type="button" className="btn btn-sm btn-outline-secondary disabled">Out of stock</button>
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            });

            return (
                <div key={'category-' + item.Category.id} className="mb-4">
                    <h6>{item.Category.name}</h6>
                    {/* <div className="table-responsive mt-3">
                        <table className="table-borderless">
                            <tr>
                                {products}
                            </tr>
                        </table>
                    </div> */}

                    <div className="table-responsive mb-3">
                        <div className="hstack gap-2 align-items-start">
                            {products}
                        </div>
                    </div>
                </div>
            );
        })

        return (
            <div>
                {content}
            </div>
        );
    }



    return <React.Fragment>
        <div>{getContent()}</div>
    </React.Fragment>
}


ReactDOM.render(<HomepageCategoryProducts></HomepageCategoryProducts>, document.getElementById('home_page_category_products'));
