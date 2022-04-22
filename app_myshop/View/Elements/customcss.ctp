<style type="text/css">
	.navbar-side {
		height: 100%;
		position: fixed;
		top: 0;
		right: 0;
		padding: 0;
		border-left: 4px solid #DDDDDD;
		overflow-y: scroll;
		z-index: 1000;

		-webkit-transform: translateX(100%);
		-ms-transform: translateX(100%);
		transform: translateX(100%);
		-webkit-transition: 300ms ease;
		transition: 300ms ease;
	}

	.navbar-side-border-bottom {
		border-bottom: 2px solid #ccc;
	}

	.navbar-side-border-top {
		border-top: 2px solid #ccc;
	}

	.side-link {
		padding-left: 2rem;
	}

	.reveal {
		-webkit-transform: translateX(0%);
		-ms-transform: translateX(0%);
		transform: translateX(0%);
		-webkit-transition: 300ms ease;
		transition: 300ms ease;
	}

	.overlay {
		position: fixed;
		display: none;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		cursor: pointer;
		background-color: #000000;
		opacity: 0.6;
		z-index: 990;
	}

	.x-small {
		font-size: 0.75rem;
	}

	.cake-sql-log td {
		color: #666;
		font-size: 80%;
		padding: 5px;
		border-top: 1px solid dodgerblue;
	}
</style>
