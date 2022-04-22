const myModal = (document.getElementById('audioNotificationModal') !== null) ? (new bootstrap.Modal(document.getElementById('audioNotificationModal'))) : null;

const playSound = () => {
    var sound = new Howl({
        src: ['/Emergency.mp3'],
        html5: true
    });
    sound.play();
}


const DeliverHeartbeat = () => {

    const [confirmedOrdersCount, setConfirmedOrdersCount] = React.useState(0);
    const [newOrdersCount, setNewOrdersCount] = React.useState(0);

    const checkNewOrders = () => {
        fetch('/deliveries/heartbeat')
            .then((response) => response.json())
            .then((response) => {
                setConfirmedOrdersCount(response.confirmedOrdersCount);
                setNewOrdersCount(response.newOrdersCount);

                setTimeout(checkNewOrders, 30000);
            })
    }

    React.useEffect(() => {
        if (myModal) {
            myModal.show();
        }

        checkNewOrders();
    }, []);

    React.useEffect(() => {
        if (newOrdersCount > 0) {
            console.log('playsound sound btn');
            playSound();
        }
    }, [newOrdersCount]);


    return (<React.Fragment>
        <span className="badge bg-orange text-orange"><i className="bi bi-bell-fill"></i> {confirmedOrdersCount}</span>
    </React.Fragment>);
}

ReactDOM.render(<DeliverHeartbeat></DeliverHeartbeat>, document.getElementById('deliveryHearbeat'));