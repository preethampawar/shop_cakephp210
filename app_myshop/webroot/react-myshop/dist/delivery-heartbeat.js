var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var myModal = document.getElementById('audioNotificationModal') !== null ? new bootstrap.Modal(document.getElementById('audioNotificationModal')) : null;

var playSound = function playSound() {
    var sound = new Howl({
        src: ['/Emergency.mp3'],
        html5: true
    });
    sound.play();
};

var DeliverHeartbeat = function DeliverHeartbeat() {
    var _React$useState = React.useState(0),
        _React$useState2 = _slicedToArray(_React$useState, 2),
        confirmedOrdersCount = _React$useState2[0],
        setConfirmedOrdersCount = _React$useState2[1];

    var _React$useState3 = React.useState(0),
        _React$useState4 = _slicedToArray(_React$useState3, 2),
        newOrdersCount = _React$useState4[0],
        setNewOrdersCount = _React$useState4[1];

    var checkNewOrders = function checkNewOrders() {
        fetch('/deliveries/heartbeat').then(function (response) {
            return response.json();
        }).then(function (response) {
            setConfirmedOrdersCount(response.confirmedOrdersCount);
            setNewOrdersCount(response.newOrdersCount);

            setTimeout(checkNewOrders, 30000);
        });
    };

    React.useEffect(function () {
        if (myModal) {
            myModal.show();
        }

        checkNewOrders();
    }, []);

    React.useEffect(function () {
        if (newOrdersCount > 0) {
            console.log('playsound sound btn');
            playSound();
        }
    }, [newOrdersCount]);

    return React.createElement(
        React.Fragment,
        null,
        React.createElement(
            'span',
            { className: 'badge bg-orange text-orange' },
            React.createElement('i', { className: 'bi bi-bell-fill' }),
            ' ',
            confirmedOrdersCount
        )
    );
};

ReactDOM.render(React.createElement(DeliverHeartbeat, null), document.getElementById('deliveryHearbeat'));