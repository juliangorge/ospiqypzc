"use strict";
angular
    .module("adminApp", ["ngAnimate", "ngSanitize", "ui.bootstrap"])
    .filter("cmdate", [
        "$filter",
        function (a) {
            return function (b, c) {
                return a("date")(new Date(b), c);
            };
        },
    ])
    .filter("uriCustom", function () {
        return function (a) {
            return encodeURI(a.toLowerCase().replace("/", "").replace("-", "").replace(/ /g, "-"));
        };
    })
    .filter("startFrom", function () {
        return function (a, b) {
            return a ? ((b = +b), a.slice(b)) : [];
        };
    })
    .filter("repeatFilter", [
        "$filter",
        function (a) {
            var b = function (a, b) {
                if (angular.isUndefined(a)) return !1;
                if (null === a || null === b) return a === b;
                if ((angular.isObject(b) && !angular.isArray(b)) || (angular.isObject(a) && !hasCustomToString(a))) return !1;
                if (((a = angular.lowercase("" + a)), angular.isArray(b))) {
                    var c = !1;
                    return (
                        b.forEach(function (b) {
                            (b = angular.lowercase("" + b)), -1 !== a.indexOf(b) && (c = !0);
                        }),
                        c
                    );
                }
                return (b = angular.lowercase("" + b)), -1 !== a.indexOf(b);
            };
            return function (c, d) {
                return a("filter")(c, d, b);
            };
        },
    ])
    .controller('GenericController', ['$scope', '$http', 'filterFilter', function (a, b, c) {
        a.init = function(json){
            a.defaultItem = null;

            $.getJSON('/admin/' + json + '/get', function (b, d, e) {
                if(d == 'success')
                {
                    a.items = b;
                    a.currentPage = 1;
                    a.totalItems = a.items.length;
                    a.entryLimit = 10;
                    a.maxPages = 10;
                    a.filter = '';
                    a.pageChanged = function () {
                        window.scroll(0, 0);
                    };
                    a.$watch('searchFilter', function () {
                        a.filtered = c(a.items, { name: a.searchFilter, id: a.selectedOption, });
                        a.totalItems = a.filtered.length;

                        a.noOfPages = function (b) {
                            return (a.totalItems = b.length), 5 < Math.ceil(a.totalItems / a.entryLimit) ? a.maxPages : Math.ceil(a.totalItems / a.entryLimit);
                        };

                        a.currentPage = 1;
                    }, true);
                    
                    a.sortedConfig = function (b) {
                        a.sorted = (b == a.sorted ? "-" + b : b);
                    };

                    a.$apply();
                }else{
                    alert('Error al solicitar datos. Contacte al desarrollador.');
                    console.log(e);
                }
            });
        };

        a.setDefaultItem = function(item_id){
            a.defaultItem = item_id;
        };
    }]);