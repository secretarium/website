<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Secretarium is a distributed confidential computing platform guaranteeing privacy by default and by design">

	<title>Secretarium - Entrusted with secrets</title>
	<link rel="icon" type="image/png" href="/images/secretarium_128x128.png">

	<script src="/scripts/modal.js"></script>

	<style>
		.sec-modal *, .sec-modal ::before, .sec-modal ::after {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
			font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
			font-size: 1rem;
			font-weight: 400;
			line-height: 1.5;
			color: #212529;
			text-align: left;
		}

		.sec-modal {
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			z-index: 1000;
			display: flex;
			visibility: hidden;
			flex-direction: column;
			align-items: center;
			overflow: hidden;
			background: rgba(0, 0, 0, .75);
			opacity: 0;
			user-select: none;
			cursor: pointer;
		}

		body.sec-modal-show {
			position: fixed;
			right: 0;
			left: 0;
			overflow: hidden;
		}
		body:not(.sec-modal-show) .sec-modal {
			display: none;
		}
		body.sec-modal-show .sec-modal {
			visibility: visible;
			opacity: 1;
		}
		body.sec-modal-show .sec-modal-box {
			animation: scale .2s cubic-bezier(.68, -.55, .265, 1.55) forwards;
		}
		.sec-modal-overflow {
			overflow-y: scroll;
			padding-top: 8vh;
			padding-bottom: 8vh;
		}

		.sec-modal-box {
			position: relative;
			flex-shrink: 0;
			margin-top: auto;
			margin-bottom: auto;
			width: 60%;
			border-radius: 4px;
			background: #fff;
			opacity: 1;
			cursor: auto;
			will-change: transform, opacity;
			background-color: #fff;
			box-shadow: 0 0 3rem 0.3rem rgba(0, 0, 0, .4);
		}

		.sec-modal-box-header {
			background-color: #f5f5f5;
			border-bottom: 0.1rem solid rgba(230,74,60,.85);
			border-radius: 0.25rem 0.25rem 0 0;
			overflow: hidden;
			position: relative;
		}
		.sec-modal-close {
			position: absolute;
			top: 1rem;
			right: 1rem;
			width: 1.25rem;
			height: 1.25rem;
			z-index: 1000;
			cursor: pointer;
			border: 0;
			background-color: inherit;
		}
		.sec-modal-close:focus { outline: 0; }
		.sec-modal-bar {
			padding: 1rem;
		}
		.sec-modal-bar > svg {
			max-width: 5.4rem;
			float: left;
			padding: .3rem 1rem 0 0;
		}
		.sec-modal-bar > h4 {
			margin-bottom: .5rem;
			font-weight: 500;
			line-height: 1.2;
			font-size: 1.5rem;
		}

		.sec-modal-box-content {
			padding: 1rem;
			border-radius: 0 0 0.25rem 0.25rem;
		}

		.sec-modal-btn {
			display: inline-block;
			font-weight: 400;
			color: #212529;
			text-align: center;
			vertical-align: middle;
			user-select: none;
			background-color: transparent;
			border: 1px solid transparent;
			padding: .375rem .75rem;
			font-size: 1rem;
			line-height: 1.5;
			border-radius: .25rem;
			transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
		}
		.sec-modal-btn:focus { outline: 0; }
		.sec-modal-btn:disabled { opacity: .65; }
		.sec-modal-btn-primary, .sec-modal-btn-primary:disabled {
			color: #fff;
			background-color: #e64a3c;
			border-color: #e64a3c;
		}
		.sec-modal-btn-primary:hover {
			color: #fff;
			background-color: #cd291a;
			border-color: #c02618;
		}
		.sec-modal-btn-primary:focus {
			box-shadow: 0 0 0 0.2rem rgba(230,74,60,0.5);
		}
		.sec-modal-btn-secondary, .sec-modal-btn-secondary:disabled {
			color: #fff;
			background-color: #6c757d;
			border-color: #6c757d;
		}
		.sec-modal-btn-secondary:hover {
			color: #fff;
			background-color: #5a6268;
			border-color: #545b62;
		}
		.sec-modal-btn-secondary:focus {
			box-shadow: 0 0 0 0.2rem rgba(130,138,145,.5);
		}

		.sec-custom-custom-checkbox {
			position: relative;
			display: block;
			min-height: 1.5rem;
			padding-left: 1.5rem;
		}
		.sec-modal-custom-checkbox input {
			position: absolute;
			z-index: -1;
			opacity: 0;
		}
		.sec-modal-custom-checkbox input:checked~label::before {
			color: #fff;
			border-color: #e64a3c;
			background-color: #e64a3c;
		}
		.sec-modal-custom-checkbox input:checked~label::after {
			background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e")
		}
		.sec-modal-custom-checkbox input:checked~label::before {
			border-color: #e64a3c;
			background-color: #e64a3c;
		}
		.sec-modal-custom-checkbox input:focus~label::before {
			box-shadow: 0 0 0 .2rem rgba(230,74,60,0.5);
		}
		.sec-modal-custom-checkbox input:focus:not(:checked)~label::before {
			border-color: rgba(230,74,60,0.5);
		}
		.sec-modal-custom-checkbox input:disabled:checked~label::before {
			background-color: rgba(230,74,60,0.7);
		}
		.sec-modal-custom-checkbox {
			padding-left: 1.5rem;
		}
		.sec-modal-custom-checkbox label {
			position: relative;
			margin-bottom: 0;
			vertical-align: top;
		}
		.sec-modal-custom-checkbox label::before {
			border-radius: .25rem;
			position: absolute;
			top: .25rem;
			left: -1.5rem;
			display: block;
			width: 1rem;
			height: 1rem;
			pointer-events: none;
			content: "";
			background-color: #fff;
			border: #adb5bd solid 1px;
		}
		.sec-modal-custom-checkbox label::after {
			position: absolute;
			top: .25rem;
			left: -1.5rem;
			display: block;
			width: 1rem;
			height: 1rem;
			content: "";
			background: no-repeat 50%/50% 50%;
		}

		.sec-modal-columns {
			margin: 1rem 0;
			column-count: auto;
			column-width: 10rem;
		}

		.sec-modal b {
    		font-weight: bold;
		}

		.sec-modal-text-red {
			color: #e64a3c;
		}

		.sec-modal-badge {
			font-size: 70%;
			padding: .05rem .2rem;
			vertical-align: .25rem;
			border-radius: .15rem;
		}

		.sec-modal hr {
			height: 1px;
			background-color: rgba(230,74,60,0.7);
			border: none;
			color: rgba(230,74,60,0.7);
		}

		@media (min-width : 700px) {
			.sec-modal-box {
				width: 700px;
			}
		}

		@media (max-width : 700px) {
			.sec-modal {
				top: 0px;
				display: block;
				padding-top: 60px;
				width: 100%;
			}

			.sec-modal-box {
				width: auto;
				border-radius: 0;
			}

			.sec-modal-box-header {
				border-radius: 0;
			}
			.sec-modal-close {
				display: none;
			}
			.sec-modal-bar > svg {
				max-width: 3rem;
    			padding: 0 .75rem 0 0;
			}
			.sec-modal-bar > h4 {
				font-size: 1rem;
    			margin: .15rem 0 .35rem 0;
			}
			.sec-modal-bar > p {
				display: none;
			}

			.sec-modal-box-content {
				overflow-y: scroll;
				border-radius: 0;
			}
		}

		@keyframes scale {
			0% {
				opacity: 0;
				transform: scale(.9);
			}
			100% {
				opacity: 1;
				transform: scale(1);
			}
		}

	</style>
</head>

<body>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
	<p>bla bla bla</p>
</body>

</html>