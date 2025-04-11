(window["webpackJsonp"] = window["webpackJsonp"] || []).push([
  [62],
  {
    /***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js":
      /*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************/
      /*! exports provided: default */
      /***/ function (module, __webpack_exports__, __webpack_require__) {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__ =
          __webpack_require__(
            /*! vuelidate/lib/validators */ "./node_modules/vuelidate/lib/validators/index.js"
          );
        /* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0___default =
          /*#__PURE__*/ __webpack_require__.n(
            vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__
          );
        /* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ =
          __webpack_require__(
            /*! vuex */ "./node_modules/vuex/dist/vuex.esm.js"
          );
        /* harmony import */ var vue_tel_input__WEBPACK_IMPORTED_MODULE_2__ =
          __webpack_require__(
            /*! vue-tel-input */ "./node_modules/vue-tel-input/dist/vue-tel-input.umd.min.js"
          );
        /* harmony import */ var vue_tel_input__WEBPACK_IMPORTED_MODULE_2___default =
          /*#__PURE__*/ __webpack_require__.n(
            vue_tel_input__WEBPACK_IMPORTED_MODULE_2__
          );
        function _typeof(o) {
          "@babel/helpers - typeof";
          return (
            (_typeof =
              "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                ? function (o) {
                    return typeof o;
                  }
                : function (o) {
                    return o &&
                      "function" == typeof Symbol &&
                      o.constructor === Symbol &&
                      o !== Symbol.prototype
                      ? "symbol"
                      : typeof o;
                  }),
            _typeof(o)
          );
        }
        function _regeneratorRuntime() {
          "use strict";
          /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime =
            function _regeneratorRuntime() {
              return e;
            };
          var t,
            e = {},
            r = Object.prototype,
            n = r.hasOwnProperty,
            o =
              Object.defineProperty ||
              function (t, e, r) {
                t[e] = r.value;
              },
            i = "function" == typeof Symbol ? Symbol : {},
            a = i.iterator || "@@iterator",
            c = i.asyncIterator || "@@asyncIterator",
            u = i.toStringTag || "@@toStringTag";
          function define(t, e, r) {
            return (
              Object.defineProperty(t, e, {
                value: r,
                enumerable: !0,
                configurable: !0,
                writable: !0,
              }),
              t[e]
            );
          }
          try {
            define({}, "");
          } catch (t) {
            define = function define(t, e, r) {
              return (t[e] = r);
            };
          }
          function wrap(t, e, r, n) {
            var i = e && e.prototype instanceof Generator ? e : Generator,
              a = Object.create(i.prototype),
              c = new Context(n || []);
            return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a;
          }
          function tryCatch(t, e, r) {
            try {
              return { type: "normal", arg: t.call(e, r) };
            } catch (t) {
              return { type: "throw", arg: t };
            }
          }
          e.wrap = wrap;
          var h = "suspendedStart",
            l = "suspendedYield",
            f = "executing",
            s = "completed",
            y = {};
          function Generator() {}
          function GeneratorFunction() {}
          function GeneratorFunctionPrototype() {}
          var p = {};
          define(p, a, function () {
            return this;
          });
          var d = Object.getPrototypeOf,
            v = d && d(d(values([])));
          v && v !== r && n.call(v, a) && (p = v);
          var g =
            (GeneratorFunctionPrototype.prototype =
            Generator.prototype =
              Object.create(p));
          function defineIteratorMethods(t) {
            ["next", "throw", "return"].forEach(function (e) {
              define(t, e, function (t) {
                return this._invoke(e, t);
              });
            });
          }
          function AsyncIterator(t, e) {
            function invoke(r, o, i, a) {
              var c = tryCatch(t[r], t, o);
              if ("throw" !== c.type) {
                var u = c.arg,
                  h = u.value;
                return h && "object" == _typeof(h) && n.call(h, "__await")
                  ? e.resolve(h.__await).then(
                      function (t) {
                        invoke("next", t, i, a);
                      },
                      function (t) {
                        invoke("throw", t, i, a);
                      }
                    )
                  : e.resolve(h).then(
                      function (t) {
                        (u.value = t), i(u);
                      },
                      function (t) {
                        return invoke("throw", t, i, a);
                      }
                    );
              }
              a(c.arg);
            }
            var r;
            o(this, "_invoke", {
              value: function value(t, n) {
                function callInvokeWithMethodAndArg() {
                  return new e(function (e, r) {
                    invoke(t, n, e, r);
                  });
                }
                return (r = r
                  ? r.then(
                      callInvokeWithMethodAndArg,
                      callInvokeWithMethodAndArg
                    )
                  : callInvokeWithMethodAndArg());
              },
            });
          }
          function makeInvokeMethod(e, r, n) {
            var o = h;
            return function (i, a) {
              if (o === f) throw Error("Generator is already running");
              if (o === s) {
                if ("throw" === i) throw a;
                return { value: t, done: !0 };
              }
              for (n.method = i, n.arg = a; ; ) {
                var c = n.delegate;
                if (c) {
                  var u = maybeInvokeDelegate(c, n);
                  if (u) {
                    if (u === y) continue;
                    return u;
                  }
                }
                if ("next" === n.method) n.sent = n._sent = n.arg;
                else if ("throw" === n.method) {
                  if (o === h) throw ((o = s), n.arg);
                  n.dispatchException(n.arg);
                } else "return" === n.method && n.abrupt("return", n.arg);
                o = f;
                var p = tryCatch(e, r, n);
                if ("normal" === p.type) {
                  if (((o = n.done ? s : l), p.arg === y)) continue;
                  return { value: p.arg, done: n.done };
                }
                "throw" === p.type &&
                  ((o = s), (n.method = "throw"), (n.arg = p.arg));
              }
            };
          }
          function maybeInvokeDelegate(e, r) {
            var n = r.method,
              o = e.iterator[n];
            if (o === t)
              return (
                (r.delegate = null),
                ("throw" === n &&
                  e.iterator["return"] &&
                  ((r.method = "return"),
                  (r.arg = t),
                  maybeInvokeDelegate(e, r),
                  "throw" === r.method)) ||
                  ("return" !== n &&
                    ((r.method = "throw"),
                    (r.arg = new TypeError(
                      "The iterator does not provide a '" + n + "' method"
                    )))),
                y
              );
            var i = tryCatch(o, e.iterator, r.arg);
            if ("throw" === i.type)
              return (
                (r.method = "throw"), (r.arg = i.arg), (r.delegate = null), y
              );
            var a = i.arg;
            return a
              ? a.done
                ? ((r[e.resultName] = a.value),
                  (r.next = e.nextLoc),
                  "return" !== r.method && ((r.method = "next"), (r.arg = t)),
                  (r.delegate = null),
                  y)
                : a
              : ((r.method = "throw"),
                (r.arg = new TypeError("iterator result is not an object")),
                (r.delegate = null),
                y);
          }
          function pushTryEntry(t) {
            var e = { tryLoc: t[0] };
            1 in t && (e.catchLoc = t[1]),
              2 in t && ((e.finallyLoc = t[2]), (e.afterLoc = t[3])),
              this.tryEntries.push(e);
          }
          function resetTryEntry(t) {
            var e = t.completion || {};
            (e.type = "normal"), delete e.arg, (t.completion = e);
          }
          function Context(t) {
            (this.tryEntries = [{ tryLoc: "root" }]),
              t.forEach(pushTryEntry, this),
              this.reset(!0);
          }
          function values(e) {
            if (e || "" === e) {
              var r = e[a];
              if (r) return r.call(e);
              if ("function" == typeof e.next) return e;
              if (!isNaN(e.length)) {
                var o = -1,
                  i = function next() {
                    for (; ++o < e.length; )
                      if (n.call(e, o))
                        return (next.value = e[o]), (next.done = !1), next;
                    return (next.value = t), (next.done = !0), next;
                  };
                return (i.next = i);
              }
            }
            throw new TypeError(_typeof(e) + " is not iterable");
          }
          return (
            (GeneratorFunction.prototype = GeneratorFunctionPrototype),
            o(g, "constructor", {
              value: GeneratorFunctionPrototype,
              configurable: !0,
            }),
            o(GeneratorFunctionPrototype, "constructor", {
              value: GeneratorFunction,
              configurable: !0,
            }),
            (GeneratorFunction.displayName = define(
              GeneratorFunctionPrototype,
              u,
              "GeneratorFunction"
            )),
            (e.isGeneratorFunction = function (t) {
              var e = "function" == typeof t && t.constructor;
              return (
                !!e &&
                (e === GeneratorFunction ||
                  "GeneratorFunction" === (e.displayName || e.name))
              );
            }),
            (e.mark = function (t) {
              return (
                Object.setPrototypeOf
                  ? Object.setPrototypeOf(t, GeneratorFunctionPrototype)
                  : ((t.__proto__ = GeneratorFunctionPrototype),
                    define(t, u, "GeneratorFunction")),
                (t.prototype = Object.create(g)),
                t
              );
            }),
            (e.awrap = function (t) {
              return { __await: t };
            }),
            defineIteratorMethods(AsyncIterator.prototype),
            define(AsyncIterator.prototype, c, function () {
              return this;
            }),
            (e.AsyncIterator = AsyncIterator),
            (e.async = function (t, r, n, o, i) {
              void 0 === i && (i = Promise);
              var a = new AsyncIterator(wrap(t, r, n, o), i);
              return e.isGeneratorFunction(r)
                ? a
                : a.next().then(function (t) {
                    return t.done ? t.value : a.next();
                  });
            }),
            defineIteratorMethods(g),
            define(g, u, "Generator"),
            define(g, a, function () {
              return this;
            }),
            define(g, "toString", function () {
              return "[object Generator]";
            }),
            (e.keys = function (t) {
              var e = Object(t),
                r = [];
              for (var n in e) r.push(n);
              return (
                r.reverse(),
                function next() {
                  for (; r.length; ) {
                    var t = r.pop();
                    if (t in e) return (next.value = t), (next.done = !1), next;
                  }
                  return (next.done = !0), next;
                }
              );
            }),
            (e.values = values),
            (Context.prototype = {
              constructor: Context,
              reset: function reset(e) {
                if (
                  ((this.prev = 0),
                  (this.next = 0),
                  (this.sent = this._sent = t),
                  (this.done = !1),
                  (this.delegate = null),
                  (this.method = "next"),
                  (this.arg = t),
                  this.tryEntries.forEach(resetTryEntry),
                  !e)
                )
                  for (var r in this)
                    "t" === r.charAt(0) &&
                      n.call(this, r) &&
                      !isNaN(+r.slice(1)) &&
                      (this[r] = t);
              },
              stop: function stop() {
                this.done = !0;
                var t = this.tryEntries[0].completion;
                if ("throw" === t.type) throw t.arg;
                return this.rval;
              },
              dispatchException: function dispatchException(e) {
                if (this.done) throw e;
                var r = this;
                function handle(n, o) {
                  return (
                    (a.type = "throw"),
                    (a.arg = e),
                    (r.next = n),
                    o && ((r.method = "next"), (r.arg = t)),
                    !!o
                  );
                }
                for (var o = this.tryEntries.length - 1; o >= 0; --o) {
                  var i = this.tryEntries[o],
                    a = i.completion;
                  if ("root" === i.tryLoc) return handle("end");
                  if (i.tryLoc <= this.prev) {
                    var c = n.call(i, "catchLoc"),
                      u = n.call(i, "finallyLoc");
                    if (c && u) {
                      if (this.prev < i.catchLoc) return handle(i.catchLoc, !0);
                      if (this.prev < i.finallyLoc) return handle(i.finallyLoc);
                    } else if (c) {
                      if (this.prev < i.catchLoc) return handle(i.catchLoc, !0);
                    } else {
                      if (!u)
                        throw Error("try statement without catch or finally");
                      if (this.prev < i.finallyLoc) return handle(i.finallyLoc);
                    }
                  }
                }
              },
              abrupt: function abrupt(t, e) {
                for (var r = this.tryEntries.length - 1; r >= 0; --r) {
                  var o = this.tryEntries[r];
                  if (
                    o.tryLoc <= this.prev &&
                    n.call(o, "finallyLoc") &&
                    this.prev < o.finallyLoc
                  ) {
                    var i = o;
                    break;
                  }
                }
                i &&
                  ("break" === t || "continue" === t) &&
                  i.tryLoc <= e &&
                  e <= i.finallyLoc &&
                  (i = null);
                var a = i ? i.completion : {};
                return (
                  (a.type = t),
                  (a.arg = e),
                  i
                    ? ((this.method = "next"), (this.next = i.finallyLoc), y)
                    : this.complete(a)
                );
              },
              complete: function complete(t, e) {
                if ("throw" === t.type) throw t.arg;
                return (
                  "break" === t.type || "continue" === t.type
                    ? (this.next = t.arg)
                    : "return" === t.type
                    ? ((this.rval = this.arg = t.arg),
                      (this.method = "return"),
                      (this.next = "end"))
                    : "normal" === t.type && e && (this.next = e),
                  y
                );
              },
              finish: function finish(t) {
                for (var e = this.tryEntries.length - 1; e >= 0; --e) {
                  var r = this.tryEntries[e];
                  if (r.finallyLoc === t)
                    return (
                      this.complete(r.completion, r.afterLoc),
                      resetTryEntry(r),
                      y
                    );
                }
              },
              catch: function _catch(t) {
                for (var e = this.tryEntries.length - 1; e >= 0; --e) {
                  var r = this.tryEntries[e];
                  if (r.tryLoc === t) {
                    var n = r.completion;
                    if ("throw" === n.type) {
                      var o = n.arg;
                      resetTryEntry(r);
                    }
                    return o;
                  }
                }
                throw Error("illegal catch attempt");
              },
              delegateYield: function delegateYield(e, r, n) {
                return (
                  (this.delegate = {
                    iterator: values(e),
                    resultName: r,
                    nextLoc: n,
                  }),
                  "next" === this.method && (this.arg = t),
                  y
                );
              },
            }),
            e
          );
        }
        function asyncGeneratorStep(n, t, e, r, o, a, c) {
          try {
            var i = n[a](c),
              u = i.value;
          } catch (n) {
            return void e(n);
          }
          i.done ? t(u) : Promise.resolve(u).then(r, o);
        }
        function _asyncToGenerator(n) {
          return function () {
            var t = this,
              e = arguments;
            return new Promise(function (r, o) {
              var a = n.apply(t, e);
              function _next(n) {
                asyncGeneratorStep(a, r, o, _next, _throw, "next", n);
              }
              function _throw(n) {
                asyncGeneratorStep(a, r, o, _next, _throw, "throw", n);
              }
              _next(void 0);
            });
          };
        }
        function ownKeys(e, r) {
          var t = Object.keys(e);
          if (Object.getOwnPropertySymbols) {
            var o = Object.getOwnPropertySymbols(e);
            r &&
              (o = o.filter(function (r) {
                return Object.getOwnPropertyDescriptor(e, r).enumerable;
              })),
              t.push.apply(t, o);
          }
          return t;
        }
        function _objectSpread(e) {
          for (var r = 1; r < arguments.length; r++) {
            var t = null != arguments[r] ? arguments[r] : {};
            r % 2
              ? ownKeys(Object(t), !0).forEach(function (r) {
                  _defineProperty(e, r, t[r]);
                })
              : Object.getOwnPropertyDescriptors
              ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t))
              : ownKeys(Object(t)).forEach(function (r) {
                  Object.defineProperty(
                    e,
                    r,
                    Object.getOwnPropertyDescriptor(t, r)
                  );
                });
          }
          return e;
        }
        function _defineProperty(e, r, t) {
          return (
            (r = _toPropertyKey(r)) in e
              ? Object.defineProperty(e, r, {
                  value: t,
                  enumerable: !0,
                  configurable: !0,
                  writable: !0,
                })
              : (e[r] = t),
            e
          );
        }
        function _toPropertyKey(t) {
          var i = _toPrimitive(t, "string");
          return "symbol" == _typeof(i) ? i : i + "";
        }
        function _toPrimitive(t, r) {
          if ("object" != _typeof(t) || !t) return t;
          var e = t[Symbol.toPrimitive];
          if (void 0 !== e) {
            var i = e.call(t, r || "default");
            if ("object" != _typeof(i)) return i;
            throw new TypeError("@@toPrimitive must return a primitive value.");
          }
          return ("string" === r ? String : Number)(t);
        }

        /* harmony default export */ __webpack_exports__["default"] = {
          data: function data() {
            return {
              loading: false,
              mobileInputProps: {
                inputOptions: {
                  type: "tel",
                  placeholder: "phone number",
                },
                dropdownOptions: {
                  showDialCodeInSelection: false,
                  showFlags: true,
                  showDialCodeInList: true,
                },
                autoDefaultCountry: false,
                validCharactersOnly: true,
                mode: "international",
              },
              form: {
                name: "",
                email: "",
                password: "",
                confirmPassword: "",
                phone: "",
                address: "",
                description: "",
                invalidPhone: true,
                showInvalidPhone: false,
              },
            };
          },
          components: {
            VueTelInput:
              vue_tel_input__WEBPACK_IMPORTED_MODULE_2__["VueTelInput"],
          },
          validations: {
            form: {
              name: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
              },
              email: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
                email:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "email"
                  ],
              },
              phone: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
              },
              password: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
                minLength: Object(
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "minLength"
                  ]
                )(6),
              },
              confirmPassword: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
                sameAsPassword: Object(
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "sameAs"
                  ]
                )("password"),
              },
              address: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
              },
              description: {
                required:
                  vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__[
                    "required"
                  ],
              },
            },
          },
          computed: _objectSpread(
            _objectSpread(
              _objectSpread(
                {},
                Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapGetters"])(
                  "auth",
                  ["isAuthenticated"]
                )
              ),
              Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapGetters"])("app", [
                "generalSettings",
                "availableCountries",
              ])
            ),
            {},
            {
              nameErrors: function nameErrors() {
                var errors = [];
                if (!this.$v.form.name.$dirty) return errors;
                !this.$v.form.name.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                return errors;
              },
              emailErrors: function emailErrors() {
                var errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                !this.$v.form.email.email &&
                  errors.push(
                    this.$i18n.t("this_field_is_required_a_valid_email")
                  );
                return errors;
              },
              passwordErrors: function passwordErrors() {
                var errors = [];
                if (!this.$v.form.password.$dirty) return errors;
                !this.$v.form.password.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                !this.$v.form.password.minLength &&
                  errors.push(
                    this.$i18n.t("password_must_be_minimum_6_characters")
                  );
                return errors;
              },
              confirmPasswordErrors: function confirmPasswordErrors() {
                var errors = [];
                if (!this.$v.form.confirmPassword.$dirty) return errors;
                !this.$v.form.confirmPassword.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                !this.$v.form.confirmPassword.sameAsPassword &&
                  errors.push(
                    this.$i18n.t("password_and_confirm_password_should_match")
                  );
                return errors;
              },
              addressErrors: function addressErrors() {
                var errors = [];
                if (!this.$v.form.address.$dirty) return errors;
                !this.$v.form.address.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                return errors;
              },
              descriptionErrors: function descriptionErrors() {
                var errors = [];
                if (!this.$v.form.description.$dirty) return errors;
                !this.$v.form.description.required &&
                  errors.push(this.$i18n.t("this_field_is_required"));
                return errors;
              },
            }
          ),
          methods: _objectSpread(
            _objectSpread(
              _objectSpread(
                _objectSpread(
                  {},
                  Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapActions"])(
                    "auth",
                    ["login"]
                  )
                ),
                Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapMutations"])(
                  "cart",
                  ["removeTempUserId"]
                )
              ),
              Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapMutations"])(
                "auth",
                ["updateChatWindow", "showLoginDialog"]
              )
            ),
            {},
            {
              phoneValidate: function phoneValidate(phone) {
                this.form.invalidPhone = phone.valid ? false : true;
                if (phone.valid) this.form.showInvalidPhone = false;
              },
              submitRequest: function submitRequest() {
                var _this = this;
                return _asyncToGenerator(
                  /*#__PURE__*/ _regeneratorRuntime().mark(function _callee() {
                    var res;
                    return _regeneratorRuntime().wrap(function _callee$(
                      _context
                    ) {
                      while (1)
                        switch ((_context.prev = _context.next)) {
                          case 0:
                            _this.$v.form.$touch();
                            if (!_this.form.invalidPhone) {
                              _context.next = 4;
                              break;
                            }
                            _this.form.showInvalidPhone = true;
                            return _context.abrupt("return");
                          case 4:
                            // if (this.$v.form.$anyError) {
                            //     return;
                            // }

                            _this.form.phone = _this.form.phone.replace(
                              /\s/g,
                              ""
                            );

                            _this.loading = true;

                            _context.next = 8;
                            return _this.call_api(
                              "post",
                              "user/affiliate/store",
                              _this.form
                            );
                          case 8:
                            res = _context.sent;

                            if (res.data.status == 400) {
                              _this.snack({
                                message: res.data.message,
                                color: "yellow",
                              });
                            }
                            if (res.data.success) {
                              _this.snack({
                                color: "green",
                                message: res.data.message,
                              });

                              _this.$router.push({
                                name: "AffiliateRegConfirmation",
                              });
                              // this.resetForm()
                            } else {
                              _this.snack({
                                message: res.data.message,
                                color: "red",
                              });
                            }
                            _this.loading = false;
                          case 13:
                          case "end":
                            return _context.stop();
                        }
                    },
                    _callee);
                  })
                )();
              },
              resetForm: function resetForm() {
                this.form.name = "";
                this.form.phone = "";
                this.form.email = "";
                this.form.invalidPhone = true;
                this.form.showInvalidPhone = false;
                this.form.address = "";
                this.form.description = "";
              },
            }
          ),
        };

        /***/
      },

    /***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e":
      /*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e ***!
  \***************************************************************************************************************************************************************************************************************************************************************/
      /*! exports provided: render, staticRenderFns */
      /***/ function (module, __webpack_exports__, __webpack_require__) {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony export (binding) */ __webpack_require__.d(
          __webpack_exports__,
          "render",
          function () {
            return render;
          }
        );
        /* harmony export (binding) */ __webpack_require__.d(
          __webpack_exports__,
          "staticRenderFns",
          function () {
            return staticRenderFns;
          }
        );
        var render = function render() {
          var _vm = this,
            _c = _vm._self._c;
          return _c(
            "v-container",
            {
              staticClass: "py-6",
            },
            [
              _c(
                "v-row",
                [
                  _c(
                    "v-col",
                    {
                      staticClass: "mx-auto",
                      attrs: {
                        cols: "12",
                        lg: "10",
                        md: "12",
                        sm: "12",
                      },
                    },
                    [
                      _c(
                        "h1",
                        {
                          staticClass: "text-h3 fw-700 mb-8",
                        },
                        [
                          _vm._v(
                            "\n                " +
                              _vm._s(_vm.$t("affiliate_informations")) +
                              "\n            "
                          ),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "v-form",
                        {
                          attrs: {
                            "lazy-validation": "",
                          },
                          on: {
                            submit: function submit($event) {
                              $event.preventDefault();
                              return _vm.submitRequest();
                            },
                          },
                        },
                        [
                          _c(
                            "v-card",
                            {
                              staticClass: "border",
                              attrs: {
                                elevation: "0",
                              },
                            },
                            [
                              _c("v-card-title", [
                                _c(
                                  "span",
                                  {
                                    staticClass: "text-h6 fw-600 opacity-80",
                                  },
                                  [_vm._v(_vm._s(_vm.$t("verification_info")))]
                                ),
                              ]),
                              _vm._v(" "),
                              _c("v-card-text", [
                                _c(
                                  "div",
                                  {
                                    staticClass: "mb-4",
                                  },
                                  [
                                    _c(
                                      "div",
                                      {
                                        staticClass: "mb-1 fs-13 fw-500",
                                      },
                                      [
                                        _vm._v(
                                          "\n                                " +
                                            _vm._s(_vm.$t("your_name")) +
                                            "\n                            "
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c("v-text-field", {
                                      attrs: {
                                        placeholder: _vm.$t("full_name"),
                                        type: "text",
                                        "error-messages": _vm.nameErrors,
                                        "hide-details": "auto",
                                        required: "",
                                        outlined: "",
                                      },
                                      on: {
                                        blur: function blur($event) {
                                          return _vm.$v.form.name.$touch();
                                        },
                                      },
                                      model: {
                                        value: _vm.form.name,
                                        callback: function callback($$v) {
                                          _vm.$set(_vm.form, "name", $$v);
                                        },
                                        expression: "form.name",
                                      },
                                    }),
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass: "mb-4",
                                  },
                                  [
                                    _c(
                                      "div",
                                      {
                                        staticClass: "fs-13 fw-500",
                                      },
                                      [
                                        _vm._v(
                                          "\n                                " +
                                            _vm._s(_vm.$t("your_email")) +
                                            "\n                            "
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c("v-text-field", {
                                      attrs: {
                                        placeholder: _vm.$t("email_address"),
                                        type: "email",
                                        "error-messages": _vm.emailErrors,
                                        "hide-details": "auto",
                                        required: "",
                                        outlined: "",
                                      },
                                      on: {
                                        blur: function blur($event) {
                                          return _vm.$v.form.email.$touch();
                                        },
                                      },
                                      model: {
                                        value: _vm.form.email,
                                        callback: function callback($$v) {
                                          _vm.$set(_vm.form, "email", $$v);
                                        },
                                        expression: "form.email",
                                      },
                                    }),
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                !_vm.isAuthenticated
                                  ? _c("div", [
                                      _c(
                                        "div",
                                        {
                                          staticClass: "mb-4",
                                        },
                                        [
                                          _c(
                                            "div",
                                            {
                                              staticClass: "mb-1 fs-13 fw-500",
                                            },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(_vm.$t("password")) +
                                                  "\n                                "
                                              ),
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c("v-text-field", {
                                            staticClass: "input-group--focused",
                                            attrs: {
                                              placeholder: "* * * * * * * *",
                                              "error-messages":
                                                _vm.passwordErrors,
                                              type: "password",
                                              "hide-details": "auto",
                                              required: "",
                                              outlined: "",
                                            },
                                            on: {
                                              blur: function blur($event) {
                                                return _vm.$v.form.password.$touch();
                                              },
                                            },
                                            model: {
                                              value: _vm.form.password,
                                              callback: function callback($$v) {
                                                _vm.$set(
                                                  _vm.form,
                                                  "password",
                                                  $$v
                                                );
                                              },
                                              expression: "form.password",
                                            },
                                          }),
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "div",
                                        {
                                          staticClass: "mb-4",
                                        },
                                        [
                                          _c(
                                            "div",
                                            {
                                              staticClass: "mb-1 fs-13 fw-500",
                                            },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(
                                                    _vm.$t("confirm_password")
                                                  ) +
                                                  "\n                                "
                                              ),
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c("v-text-field", {
                                            staticClass: "input-group--focused",
                                            attrs: {
                                              placeholder: "* * * * * * * *",
                                              "error-messages":
                                                _vm.confirmPasswordErrors,
                                              type: "password",
                                              "hide-details": "auto",
                                              required: "",
                                              outlined: "",
                                            },
                                            on: {
                                              blur: function blur($event) {
                                                return _vm.$v.form.confirmPassword.$touch();
                                              },
                                            },
                                            model: {
                                              value: _vm.form.confirmPassword,
                                              callback: function callback($$v) {
                                                _vm.$set(
                                                  _vm.form,
                                                  "confirmPassword",
                                                  $$v
                                                );
                                              },
                                              expression:
                                                "form.confirmPassword",
                                            },
                                          }),
                                        ],
                                        1
                                      ),
                                    ])
                                  : _vm._e(),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass: "mb-4",
                                  },
                                  [
                                    _c(
                                      "div",
                                      {
                                        staticClass: "mb-1 fs-13 fw-500",
                                      },
                                      [
                                        _vm._v(
                                          "\n                                " +
                                            _vm._s(_vm.$t("phone_number")) +
                                            "\n                            "
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "vue-tel-input",
                                      _vm._b(
                                        {
                                          attrs: {
                                            onlyCountries:
                                              _vm.availableCountries,
                                          },
                                          on: {
                                            validate: _vm.phoneValidate,
                                          },
                                          model: {
                                            value: _vm.form.phone,
                                            callback: function callback($$v) {
                                              _vm.$set(_vm.form, "phone", $$v);
                                            },
                                            expression: "form.phone",
                                          },
                                        },
                                        "vue-tel-input",
                                        _vm.mobileInputProps,
                                        false
                                      ),
                                      [
                                        _c(
                                          "template",
                                          {
                                            slot: "arrow-icon",
                                          },
                                          [
                                            _c(
                                              "span",
                                              {
                                                staticClass:
                                                  "vti__dropdown-arrow",
                                              },
                                              [_vm._v(" ▼")]
                                            ),
                                          ]
                                        ),
                                      ],
                                      2
                                    ),
                                    _vm._v(" "),
                                    _vm.$v.form.phone.$error
                                      ? _c(
                                          "div",
                                          {
                                            staticClass:
                                              "v-text-field__details mt-2 pl-3",
                                          },
                                          [
                                            _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "v-messages theme--light error--text",
                                                attrs: {
                                                  role: "alert",
                                                },
                                              },
                                              [
                                                _c(
                                                  "div",
                                                  {
                                                    staticClass:
                                                      "v-messages__wrapper",
                                                  },
                                                  [
                                                    _c(
                                                      "div",
                                                      {
                                                        staticClass:
                                                          "v-messages__message",
                                                      },
                                                      [
                                                        _vm._v(
                                                          "\n                                            " +
                                                            _vm._s(
                                                              _vm.$t(
                                                                "this_field_is_required"
                                                              )
                                                            ) +
                                                            "\n                                        "
                                                        ),
                                                      ]
                                                    ),
                                                  ]
                                                ),
                                              ]
                                            ),
                                          ]
                                        )
                                      : _vm._e(),
                                    _vm._v(" "),
                                    !_vm.$v.form.phone.$error &&
                                    _vm.form.showInvalidPhone
                                      ? _c(
                                          "div",
                                          {
                                            staticClass:
                                              "v-text-field__details mt-2 pl-3",
                                          },
                                          [
                                            _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "v-messages theme--light error--text",
                                                attrs: {
                                                  role: "alert",
                                                },
                                              },
                                              [
                                                _c(
                                                  "div",
                                                  {
                                                    staticClass:
                                                      "v-messages__wrapper",
                                                  },
                                                  [
                                                    _c(
                                                      "div",
                                                      {
                                                        staticClass:
                                                          "v-messages__message",
                                                      },
                                                      [
                                                        _vm._v(
                                                          "\n                                            " +
                                                            _vm._s(
                                                              _vm.$t(
                                                                "phone_number_must_be_valid"
                                                              )
                                                            ) +
                                                            "\n                                        "
                                                        ),
                                                      ]
                                                    ),
                                                  ]
                                                ),
                                              ]
                                            ),
                                          ]
                                        )
                                      : _vm._e(),
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass: "mb-4",
                                  },
                                  [
                                    _c(
                                      "div",
                                      {
                                        staticClass: "mb-1 fs-13 fw-500",
                                      },
                                      [
                                        _vm._v(
                                          "\n                                " +
                                            _vm._s(_vm.$t("full_address")) +
                                            "\n                            "
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c("v-text-field", {
                                      attrs: {
                                        placeholder: _vm.$t("full_address"),
                                        type: "text",
                                        "error-messages": _vm.addressErrors,
                                        "hide-details": "auto",
                                        required: "",
                                        outlined: "",
                                      },
                                      on: {
                                        blur: function blur($event) {
                                          return _vm.$v.form.address.$touch();
                                        },
                                      },
                                      model: {
                                        value: _vm.form.address,
                                        callback: function callback($$v) {
                                          _vm.$set(_vm.form, "address", $$v);
                                        },
                                        expression: "form.address",
                                      },
                                    }),
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass: "mb-4",
                                  },
                                  [
                                    _c(
                                      "div",
                                      {
                                        staticClass: "mb-1 fs-13 fw-500",
                                      },
                                      [
                                        _vm._v(
                                          "\n                                " +
                                            _vm._s(
                                              _vm.$t("how_will_you_affiliate?")
                                            ) +
                                            "\n                            "
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c("v-text-field", {
                                      attrs: {
                                        placeholder: _vm.$t("description"),
                                        type: "text",
                                        "error-messages": _vm.descriptionErrors,
                                        "hide-details": "auto",
                                        required: "",
                                        outlined: "",
                                      },
                                      on: {
                                        blur: function blur($event) {
                                          return _vm.$v.form.description.$touch();
                                        },
                                      },
                                      model: {
                                        value: _vm.form.description,
                                        callback: function callback($$v) {
                                          _vm.$set(
                                            _vm.form,
                                            "description",
                                            $$v
                                          );
                                        },
                                        expression: "form.description",
                                      },
                                    }),
                                  ],
                                  1
                                ),
                              ]),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-btn",
                            {
                              staticClass: "px-12 mb-4 w-100 mt-5",
                              attrs: {
                                "x-large": "",
                                elevation: "0",
                                type: "submit",
                                color: "primary",
                                loading: _vm.loading,
                                disabled: _vm.loading,
                              },
                              on: {
                                click: _vm.submitRequest,
                              },
                            },
                            [_vm._v(_vm._s(_vm.$t("save")))]
                          ),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                ],
                1
              ),
            ],
            1
          );
        };
        var staticRenderFns = [];
        render._withStripped = true;

        /***/
      },

    /***/ "./resources/js/pages/user/affiliate/AffiliateRegistration.vue":
      /*!*********************************************************************!*\
  !*** ./resources/js/pages/user/affiliate/AffiliateRegistration.vue ***!
  \*********************************************************************/
      /*! exports provided: default */
      /***/ function (module, __webpack_exports__, __webpack_require__) {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var _AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__ =
          __webpack_require__(
            /*! ./AffiliateRegistration.vue?vue&type=template&id=03a8b82e */ "./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e"
          );
        /* harmony import */ var _AffiliateRegistration_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ =
          __webpack_require__(
            /*! ./AffiliateRegistration.vue?vue&type=script&lang=js */ "./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js"
          );
        /* empty/unused harmony star reexport */ /* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ =
          __webpack_require__(
            /*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js"
          );

        /* normalize component */

        var component = Object(
          _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[
            "default"
          ]
        )(
          _AffiliateRegistration_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__[
            "default"
          ],
          _AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__[
            "render"
          ],
          _AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__[
            "staticRenderFns"
          ],
          false,
          null,
          null,
          null
        );

        /* hot reload */
        if (false) {
          var api;
        }
        component.options.__file =
          "resources/js/pages/user/affiliate/AffiliateRegistration.vue";
        /* harmony default export */ __webpack_exports__["default"] =
          component.exports;

        /***/
      },

    /***/ "./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js":
      /*!*********************************************************************************************!*\
  !*** ./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js ***!
  \*********************************************************************************************/
      /*! exports provided: default */
      /***/ function (module, __webpack_exports__, __webpack_require__) {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AffiliateRegistration_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ =
          __webpack_require__(
            /*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AffiliateRegistration.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=script&lang=js"
          );
        /* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[
          "default"
        ] =
          _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AffiliateRegistration_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__[
            "default"
          ];

        /***/
      },

    /***/ "./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e":
      /*!***************************************************************************************************!*\
  !*** ./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e ***!
  \***************************************************************************************************/
      /*! exports provided: render, staticRenderFns */
      /***/ function (module, __webpack_exports__, __webpack_require__) {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__ =
          __webpack_require__(
            /*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AffiliateRegistration.vue?vue&type=template&id=03a8b82e */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/user/affiliate/AffiliateRegistration.vue?vue&type=template&id=03a8b82e"
          );
        /* harmony reexport (safe) */ __webpack_require__.d(
          __webpack_exports__,
          "render",
          function () {
            return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__[
              "render"
            ];
          }
        );

        /* harmony reexport (safe) */ __webpack_require__.d(
          __webpack_exports__,
          "staticRenderFns",
          function () {
            return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_AffiliateRegistration_vue_vue_type_template_id_03a8b82e__WEBPACK_IMPORTED_MODULE_0__[
              "staticRenderFns"
            ];
          }
        );

        /***/
      },
  },
]);
