/**
 * Step Factory
 */
(function () {
    'use strict';

    angular.module('StepModule').factory('StepFactory', [
        'PathFactory',
        function (PathFactory) {
            // Stored step
            var step = null;

            // Base template used to append new step to tree
            var baseStep = {
                id                : null,
                lvl               : 0,
                resourceId        : null,
                activityId        : null,
                name              : 'Step',
                description       : null,
                durationHours     : 0,
                durationMinutes   : 0,
                who               : null,
                where             : null,
                withTutor         : true,
                children          : [],
                primaryResource   : null,
                resources         : [],
                excludedResources : []
            };

            return {
                /**
                 * Generate a new empty step
                 *
                 * @param   object step
                 * @returns object
                 */
                generateNewStep: function (step) {
                    var stepId = PathFactory.getNextStepId();
                    var newStep = jQuery.extend(true, {}, baseStep);

                    if (undefined != step && null != step) {
                        newStep.name = 'Step' + '-' + stepId;
                        newStep.lvl = step.lvl + 1;
                    }

                    newStep.id = stepId;

                    return newStep;
                },

                /**
                 * Store step in factory
                 *
                 * @param   object data - The step to store
                 * @returns StepFactory
                 */
                setStep: function (data) {
                    step = data;

                    return this;
                },

                /**
                 * Get step stored in factory
                 *
                 * @returns object
                 */
                getStep: function () {
                    return step;
                },

                /**
                 * Search resource in path and replace it by a new one
                 *
                 * @param   object newResource
                 * @returns StepFactory
                 */
                replaceResource: function (newResource) {
                    if (null !== step && typeof step.resources !== 'undefined' && null !== step.resources) {
                        for (var i = 0; i < step.resources.length; i++) {
                            if (newResource.id === step.resources[i].id) {
                                this.updateResource(oldResource, newResource);
                                break;
                            }
                        }
                    }

                    return this;
                },

                /**
                 * Update resource properties
                 *
                 * @param   object oldResource
                 * @param   object newResource
                 * @returns StepFactory
                 */
                updateResource: function (oldResource, newResource) {
                    for (var prop in newResource) {
                        oldResource[prop] = newResource[prop];
                    }

                    return this;
                },

                /**
                 * Remove selected resource from step
                 *
                 * @param   object step          current step
                 * @param   string resourceId    resource to remove
                 * @returns StepFactory
                 */
                removeResource: function (step, resourceId) {
                    if (typeof step.resources !== 'undefined' && null !== step.resources)
                    // Search resource to remove
                        for (var i = 0; i < step.resources.length; i++) {
                            if (resourceId === step.resources[i].id) {
                                step.resources.splice(i, 1);
                                break;
                            }
                        }

                    return this;
                },

                /**
                 * Check if a step contains a resource a not
                 * @param object step
                 * @param integer resourceId
                 */
                hasResource: function (step, resourceId) {
                    var resourceExists = false;
                    for (var i = 0; i < step.resources.length; i++) {
                        var res = step.resources[i];
                        if (res.resourceId === resourceId) {
                            resourceExists = true;
                            break;
                        }
                    }

                    return resourceExists;
                }
            };
        }
    ]);
})();