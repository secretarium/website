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
			"key": "xF2IUJnynRkkMvD+3RbYjPC/RgazPG3YY6GzVPTT8/WKRO6/1DuRzFFljtvESb/YdmOk7kudprH6L4z3tYPqPg==",
			"gateways": [
				{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE-1)" },
				{ "endpoint": "wss://ovh4.node.secretarium.org:443/", "name": "DEMO (OVH UK-1)" },
				{ "endpoint": "wss://ovh5.node.secretarium.org:443/", "name": "DEMO (OVH DE-2)" },
				{ "endpoint": "wss://ovh6.node.secretarium.org:443/", "name": "DEMO (OVH UK-2)" }
			]
		}
<?php } else if($env == "dev") { ?>		"sec-demo-1": {
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"gateways": [
				{ "endpoint": "wss://127.0.0.1:5428/", "name": "TEST LOCAL" }
			]
		}
<?php } ?>
	},
	"dcapps": {
		"madrec": {
			"name": "madrec",
			"display": "MADRec",
			"description": "Massive Anonymous Data Reconciliation. Collectively measure reference data quality.",
			"icon": "fa-chart-pie",
			"cluster" : "sec-demo-1",
			"ui": {
				"src": "/dcapps-demo/madrec.php",
				"require": [
					{ "name": "papaparse-4.6.0", "src": "/scripts/papaparse-4.6.0.min.js" },
					{ "name": "chart-2.8.0", "src": "/scripts/chart-2.8.0.min.js"},
					{ "name": "stream-saver-0.0.0", "src": "/scripts/stream-saver-0.0.0.js" },
					{ "name": "madrec-formats-0.0.15", "src": "/scripts/madrec.formats-0.0.15.js" }
				]
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