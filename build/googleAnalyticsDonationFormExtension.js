/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/FormExtension/DonationForm/Templates/GoogleAnalyticsField.tsx":
/*!***************************************************************************!*\
  !*** ./src/FormExtension/DonationForm/Templates/GoogleAnalyticsField.tsx ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ GoogleAnalyticsField)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var ga_gtag__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ga-gtag */ "./node_modules/ga-gtag/lib/index.js");
/* harmony import */ var _Utilities__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Utilities */ "./src/FormExtension/DonationForm/Templates/Utilities.ts");




function GoogleAnalyticsField({
  trackingId,
  affiliation,
  trackCategory,
  trackListName,
  trackingMode,
  administrator
}) {
  const {
    useFormContext
  } = window.givewp.form.hooks;
  const {
    formTitle
  } = window.givewp.form.hooks.useDonationFormSettings();
  const shouldDisableTracking = administrator || !trackingMode;
  if (shouldDisableTracking) {
    return false;
  }
  const {
    formState: {
      defaultValues: {
        formId,
        amount,
        currency
      },
      isSubmitting
    },
    getValues
  } = useFormContext();
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    (0,ga_gtag__WEBPACK_IMPORTED_MODULE_2__.install)(trackingId);
    (0,_Utilities__WEBPACK_IMPORTED_MODULE_3__.trackPageView)();
    (0,_Utilities__WEBPACK_IMPORTED_MODULE_3__.trackViewItem)(formId, formTitle, amount, currency, affiliation, trackCategory, trackListName);
  }, []);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (isSubmitting) {
      const submittedValues = getValues();
      (0,_Utilities__WEBPACK_IMPORTED_MODULE_3__.trackBeginCheckout)(submittedValues, formId, formTitle, affiliation, trackCategory, trackListName);
    }
  }, [isSubmitting, getValues]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "givewp-google-analytics-hidden-element"
  });
}

/***/ }),

/***/ "./src/FormExtension/DonationForm/Templates/Utilities.ts":
/*!***************************************************************!*\
  !*** ./src/FormExtension/DonationForm/Templates/Utilities.ts ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "trackBeginCheckout": () => (/* binding */ trackBeginCheckout),
/* harmony export */   "trackPageView": () => (/* binding */ trackPageView),
/* harmony export */   "trackViewItem": () => (/* binding */ trackViewItem)
/* harmony export */ });
/* harmony import */ var ga_gtag__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ga-gtag */ "./node_modules/ga-gtag/lib/index.js");

function trackPageView() {
  (0,ga_gtag__WEBPACK_IMPORTED_MODULE_0__.gtag)("event", "page_view", {
    page_title: window.parent.document.title
  });
}
function trackViewItem(formId, formTitle, amount, currency, affiliation, trackCategory, trackListName) {
  (0,ga_gtag__WEBPACK_IMPORTED_MODULE_0__.gtag)("event", "view_item", {
    currency: currency,
    value: amount,
    items: [{
      item_id: formId,
      item_name: formTitle,
      item_brand: "Fundraising",
      affiliation: affiliation,
      item_category: trackCategory,
      item_list_name: trackListName
    }]
  });
}
function trackBeginCheckout(submittedValues, formId, formTitle, affiliation, trackCategory, trackListName) {
  const {
    amount,
    currency,
    donationType,
    gatewayId
  } = submittedValues;
  (0,ga_gtag__WEBPACK_IMPORTED_MODULE_0__.gtag)("event", "begin_checkout", {
    currency: currency,
    value: amount,
    items: [{
      item_id: formId,
      item_name: formTitle,
      item_brand: "Fundraising",
      affiliation: affiliation,
      item_category: trackCategory,
      item_category2: gatewayId,
      item_category3: donationType,
      item_list_name: trackListName,
      price: amount,
      quantity: 1
    }]
  });
}

/***/ }),

/***/ "./node_modules/ga-gtag/lib/index.js":
/*!*******************************************!*\
  !*** ./node_modules/ga-gtag/lib/index.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, exports) => {

Object.defineProperty(exports, "__esModule", ({value:true}));exports.install=exports.gtag=exports["default"]=void 0;var install=function install(trackingId){var additionalConfigInfo=arguments.length>1&&arguments[1]!==undefined?arguments[1]:{};var scriptId="ga-gtag";if(document.getElementById(scriptId))return;var _document=document,head=_document.head;var script=document.createElement("script");script.id=scriptId;script.async=true;script.src="https://www.googletagmanager.com/gtag/js?id=".concat(trackingId);head.insertBefore(script,head.firstChild);window.dataLayer=window.dataLayer||[];gtag("js",new Date);gtag("config",trackingId,additionalConfigInfo)};exports.install=install;var gtag=function gtag(){window.dataLayer.push(arguments)};exports.gtag=gtag;var _default=gtag;exports["default"]=_default;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************!*\
  !*** ./src/FormExtension/DonationForm/index.ts ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Templates_GoogleAnalyticsField__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Templates/GoogleAnalyticsField */ "./src/FormExtension/DonationForm/Templates/GoogleAnalyticsField.tsx");


// @ts-ignore
window.givewp.form.templates.elements.googleAnalytics = _Templates_GoogleAnalyticsField__WEBPACK_IMPORTED_MODULE_0__["default"];
})();

/******/ })()
;
//# sourceMappingURL=googleAnalyticsDonationFormExtension.js.map