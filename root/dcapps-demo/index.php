<?php
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if(preg_match("/\.secretarium\.(com|dev|org)$/", $http_origin)) {
        header("Access-Control-Allow-Origin: $http_origin");
    }
	header('Content-type:application/json;charset=utf-8');

	$env = "prod";
	if (substr($_SERVER['SERVER_NAME'], 0, 3) == "uat") $env = "uat";
	else if (substr($_SERVER['SERVER_NAME'], -3) == "dev") $env = "dev";
?>
{
	"clusters": {
<?php if($env == "prod") { ?>		"sec-demo-1": {
			"key": "SOOXTo9EpqZzSuStIPeB7D2uO+5gQ4S+Q7H/I4JlV/LJU14DMR+d/DyD4iL+O77GvznSEIq9I+YHN0N4QB/96w==",
			"gateways": [
				{ "endpoint": "wss://ovh-fr-rbx-2288-1.node.secretarium.org:443/", "name": "DEMO (OVH UK-1)" },
				{ "endpoint": "wss://ovh-fr-rbx-2288-1.node.secretarium.org:443/", "name": "DEMO (OVH DE-1)" },
				{ "endpoint": "wss://ovh-fr-rbx-2288-1.node.secretarium.org:443/", "name": "DEMO (OVH DE-2)" }
			]
		}
<?php } else if($env == "dev") { ?>		"sec-demo-1": {
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"gateways": [
				{ "endpoint": "wss://127.0.0.1:5428/", "name": "TEST LOCAL" },
				{ "endpoint": "wss://192.168.1.232:443/", "name": "TEST GH" },
				{ "endpoint": "wss://dev.node.secretarium.org:5557/", "name": "TEST DEV 5557 (via dyn dns)" }
			]
		}
<?php } ?>
	},
	"dcapps": {
		"smex": {
			"name": "smex",
			"display": "Molecule Research Exchange",
			"description": "Secure reconciliation of research on molecules and exchange facility",
			"icon": "fa-atom",
			"cluster" : "sec-demo-1",
			"ui": {
				"templates": "/dcapps-demo/smex.php",
				"scripts": "/dcapps-demo/smex-0.0.1.js",
				"onboarding": true
			}
		},
		"semaphore": {
			"name": "semaphore",
			"display": "Semaphore",
			"description": "Access critical intelligence about your clients from peers to support KYC, AML and compliance",
			"icon": "fa-sync-alt",
			"cluster" : "sec-demo-1",
			"ui": {
				"require": [
					{ "name": "papaparse-4.6.0", "src": "/scripts/papaparse-4.6.0.min.js" },
					{ "name": "stream-saver-0.0.0", "src": "/scripts/stream-saver-0.0.0.js" },
					{ "name": "semaphore-formats-0.0.1", "src": "/dcapps-demo/semaphore.formats-0.0.2.js" }
				],
				"templates": "/dcapps-demo/semaphore.php",
				"scripts": "/dcapps-demo/semaphore-0.0.2.js",
				"onboarding": true
			}
		},
		"sbx": {
			"name": "sbx",
			"display": "Crypto Exchange",
			"description": "Crypto currency order book to facilitate HTLCs (cross-chain atomic swaps)",
			"icon": "fa-exchange-alt",
			"cluster" : "sec-demo-1"
		},
		"cdnn": {
			"name": "cdnn",
			"display": "Machine Learning",
			"description": "IA calibration on confidential samples",
			"icon": "fa-brain",
			"cluster" : "sec-demo-1"
		},
		"fcp": {
			"name": "fcp",
			"display": "Fund Client Pooling",
			"description": "A tool to perform confidential statistics on fund distribution",
			"icon": "fa-grip-horizontal",
			"cluster" : "sec-demo-1"
		},
		"inconel": {
			"name": "inconel",
			"display": "Inconel",
			"description": "Real time consensus analytics and pricing for OTC market",
			"icon": "fa-chart-bar",
			"cluster" : "sec-demo-1"
		},
		"frtb": {
			"name": "frtb",
			"display": "FRTB Data Pooling",
			"description": "Collectively prove liquidity under FRTB regulation",
			"icon": "fa-compress",
			"cluster" : "sec-demo-1"
		},
		"me": {
			"name": "me",
			"display": "Personal Records",
			"description": "Get your personal records verified and become identifiable",
			"icon": "fa-id-card",
			"cluster" : "sec-demo-1",
			"loaded": true
		},
		"monitoring": {
			"name": "monitoring",
			"display": "Monitoring",
			"description": "Health and state of Secretarium networks",
			"icon": "fa-tasks",
			"cluster" : "sec-demo-1"
		}
	}
}