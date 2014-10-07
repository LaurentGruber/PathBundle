'use strict';

// Declare app level module which depends on filters, and services
var EditorApp = angular.module('EditorApp', [
    'ngRoute', 
    'ngSanitize',
    'ui.bootstrap', 
    'ui.pageslide', 
    'ui.tinymce',
    'ui.translation',
    'ui.resourcePicker',
    'ui.tree'
]);

// History
EditorApp.factory('HistoryFactory', HistoryFactory);

// Clipboard
EditorApp.factory('ClipboardFactory', ClipboardFactory);

// Alerts
EditorApp.factory('AlertFactory', AlertFactory);
EditorApp.controller('AlertCtrl', AlertCtrl);

EditorApp.controller('ConfirmModalCtrl', ConfirmModalCtrl);

// Main
EditorApp.controller('MainCtrl', MainCtrl);

// Path
EditorApp.factory('PathFactory', PathFactory);
EditorApp.controller('GlobalCtrl', GlobalCtrl);
EditorApp.controller('ScenarioCtrl', ScenarioCtrl);
EditorApp.controller('StepCtrl', StepCtrl);
EditorApp.controller('ConfirmExitModalCtrl', ConfirmExitModalCtrl);
EditorApp.controller('TemplateResourcesModalCtrl', TemplateResourcesModalCtrl);
EditorApp.filter('path_to_json', PathToJsonFilter);

// Steps
EditorApp.factory('StepFactory', StepFactory);

// Resources
EditorApp.factory('ResourceFactory', ResourceFactory);

// Templates
EditorApp.factory('TemplateFactory', TemplateFactory);
EditorApp.controller('TemplateCtrl', TemplateCtrl);
EditorApp.controller('TemplateModalCtrl', TemplateModalCtrl);

EditorApp.filter('truncate', TruncateTextFilter);