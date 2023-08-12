/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/FormExtension/FormBuilder/Block/edit/BlockInspectorControls.tsx":
/*!*****************************************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/edit/BlockInspectorControls.tsx ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ BlockInspectorControls)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _GlobalSettingsLink__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./GlobalSettingsLink */ "./src/FormExtension/FormBuilder/Block/edit/GlobalSettingsLink.tsx");





function BlockInspectorControls({
  attributes
}) {
  const {
    useGlobalSettings
  } = attributes;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Field Settings", "give-fee-recovery"),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Google Analytics 4", "give"),
    onChange: null,
    value: useGlobalSettings,
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Global", "give"),
      value: "true"
    }]
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_GlobalSettingsLink__WEBPACK_IMPORTED_MODULE_4__["default"], {
    href: "/wp-admin/edit.php?post_type=give_forms&page=give-settings&tab=general&section=google-analytics"
  })));
}

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/edit/BlockPlaceholder.tsx":
/*!***********************************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/edit/BlockPlaceholder.tsx ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ BlockPlaceholder)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Icon */ "./src/FormExtension/FormBuilder/Block/edit/Icon.tsx");



function BlockPlaceholder() {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      padding: "30px 20px",
      display: "flex",
      gap: ".75rem",
      fontSize: "1rem",
      border: " 1px dashed var(--givewp-gray-100)",
      borderRadius: "5px",
      backgroundColor: "var(--givewp-gray-10)"
    }
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], null), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Google Analytics 4 is enabled and tracking data for this form."));
}

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/edit/GlobalSettingsLink.tsx":
/*!*************************************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/edit/GlobalSettingsLink.tsx ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ GlobalSettingsLink)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


function GlobalSettingsLink({
  href
}) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    style: {
      color: '#595959',
      fontStyle: 'SF Pro Text',
      fontSize: '0.75rem',
      lineHeight: '140%',
      fontWeight: 400
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' Go to the settings to change the '), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: href
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('global options.')));
}

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/edit/Icon.tsx":
/*!***********************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/edit/Icon.tsx ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Icon)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

function Icon() {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    width: "20",
    height: "20",
    viewBox: "0 0 20 20",
    fill: "none"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M10 .833a9.167 9.167 0 1 0 0 18.334A9.167 9.167 0 0 0 10 .833zm0 5A.833.833 0 0 0 10 7.5h.008a.833.833 0 0 0 0-1.667H10zM10.833 10a.833.833 0 0 0-1.666 0v3.333a.833.833 0 0 0 1.666 0V10z",
    fill: "#000000"
  }));
}

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/edit/index.tsx":
/*!************************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/edit/index.tsx ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ index)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _BlockPlaceholder__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BlockPlaceholder */ "./src/FormExtension/FormBuilder/Block/edit/BlockPlaceholder.tsx");
/* harmony import */ var _BlockInspectorControls__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./BlockInspectorControls */ "./src/FormExtension/FormBuilder/Block/edit/BlockInspectorControls.tsx");



function index({
  attributes
}) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_BlockPlaceholder__WEBPACK_IMPORTED_MODULE_1__["default"], null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_BlockInspectorControls__WEBPACK_IMPORTED_MODULE_2__["default"], {
    attributes: attributes
  }));
}

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/index.tsx":
/*!*******************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/index.tsx ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _metadata__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./metadata */ "./src/FormExtension/FormBuilder/Block/metadata.ts");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/FormExtension/FormBuilder/Block/edit/index.tsx");


const {
  name
} = _metadata__WEBPACK_IMPORTED_MODULE_0__["default"];
const save = () => null;
const settings = {
  ..._metadata__WEBPACK_IMPORTED_MODULE_0__["default"],
  save,
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"]
};
const googleAnalytics = {
  name,
  settings
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (googleAnalytics);

/***/ }),

/***/ "./src/FormExtension/FormBuilder/Block/metadata.ts":
/*!*********************************************************!*\
  !*** ./src/FormExtension/FormBuilder/Block/metadata.ts ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);

const metadata = {
  name: "givewp-google-analytics/google-analytics",
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Google Analytics", "give-google-analytics"),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Hidden field to manage google analytics.", "give-google-analytics"),
  category: "addons",
  supports: {
    multiple: false
  },
  attributes: {
    useGlobalSettings: {
      type: "string",
      default: "true"
    },
    trackingId: {
      type: "string",
      default: ""
    },
    trackTestDonations: {
      type: "boolean"
    },
    trackRefunds: {
      type: "boolean"
    },
    trackingValues: {
      type: "string",
      default: "default"
    },
    affiliation: {
      type: "string",
      default: ""
    },
    trackingCategory: {
      type: "string",
      default: ""
    },
    trackingListName: {
      type: "string",
      default: ""
    }
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (metadata);

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

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
/*!************************************************!*\
  !*** ./src/FormExtension/FormBuilder/index.ts ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Block__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Block */ "./src/FormExtension/FormBuilder/Block/index.tsx");

// @ts-ignore
window.givewp.form.blocks.register(_Block__WEBPACK_IMPORTED_MODULE_0__["default"].name, _Block__WEBPACK_IMPORTED_MODULE_0__["default"].settings);
})();

/******/ })()
;
//# sourceMappingURL=googleAnalyticsFormBuilderExtension.js.map