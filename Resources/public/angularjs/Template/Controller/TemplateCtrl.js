/**
 * Template Controller
 */
(function () {
    'use strict';

    angular.module('TemplateModule').controller('TemplateCtrl', [
        '$scope',
        '$http',
        '$modal',
        'TemplateFactory',
        function ($scope, $http, $modal, TemplateFactory) {
            $scope.templates = [];

            // Load templates list from AJAX
            $http.get(Routing.generate('innova_path_template_list'))
                .then(function(response) {
                    TemplateFactory.setTemplates(response.data);
                    $scope.templates = TemplateFactory.getTemplates();
                });

            /**
             * Open modal to edit specified template properties
             * @returns void
             */
            $scope.edit = function(template) {
                TemplateFactory.setCurrentTemplate(template);
                var modalInstance = $modal.open({
                    templateUrl: EditorApp.webDir + 'bundles/innovapath/angularjs/Template/Partial/template-edit.html',
                    controller: 'TemplateModalCtrl'
                });
            };

            /**
             * Delete specified template
             * @returns void
             */
            $scope.remove = function(template) {
                $http({
                    method: 'DELETE',
                    url: Routing.generate('innova_path_template_delete', {id: template.id})
                })
                    .success(function (data) {
                        for (var i = 0; i < $scope.templates.length; i++) {
                            if ($scope.templates[i].id == template.id) {
                                // Template to delete found
                                $scope.templates.splice(i, 1);
                                TemplateFactory.setTemplates($scope.templates);
                                break;
                            }
                        }
                    })
                    .error(function(data, status) {
                        AlertFactory.addAlert('error', 'Error while removing Path template.');
                    });
            };
        }
    ]);
})();