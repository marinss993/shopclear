<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_documentation extends JshoppingModelAddon_api {

        public function generate(string $path = ''): bool {
            $addon                         = AddonApi::getInst();
            $view                          = $this->getView('index');
            $view->author                  = $addon->getParam('author');
            $view->author_email            = $addon->getParam('author_email');
            $view->author_url              = $addon->getParam('author_url');
            $view->css                     = $this->renderCss();
            $view->date                    = $addon->getParam('date');
            $view->js                      = $this->renderJs();
            $view->reports                 = $this->renderReports();
            $view->version                 = $addon->getParam('version');
            $view->version_history         = $this->renderVersionHistory();
            $view->sections_and_tasks      = $this->renderSectionsAndTasks();
            $view->sections_and_tasks_menu = $this->renderSectionsAndTasksMenu();
            return (bool) file_put_contents(
                JPath::clean(
                    $path
                        ? $path
                        : (
                            $addon->getParam('dirs_pathes[files]') .
                            $addon->getAlias() . '_documentation.htm'
                        )
                ),
                $view->loadTemplate()
            );
        }

        public function getSectionsAndTasks(): array {
            $addon       = AddonApi::getInst();
            $file        = $addon->getAlias() . '_sections_and_tasks.php';
            $addons_path = dirname($addon->getParam('dirs_pathes[addons]'));
            require (
                $addon->getParam('dirs_pathes[addons]') .
                'sections_and_tasks.php'
            );
            $res = $sections_and_tasks;
            foreach (JFolder::folders($addons_path) as $addon) {
                $path = JPath::clean($addons_path . '/' . $addon . '/' . $file);
                if (!JFile::exists($path)) {
                    continue;
                }
                require $path;
                $res = $res + $sections_and_tasks;
            }
            ksort($res);
            return $res;
        }

        public function getVersionHistory(): array {
            require (
                AddonApi::getInst()->getParam('dirs_pathes[addons]') .
                'version_history.php'
            );
            return $version_history;
        }

        public function renderCss(): string {
            return (string) file_get_contents(
                AddonApi::getInst()->getParam('dirs_pathes[css]') .
                'documentation.css'
            );
        }

        public function renderJs(): string {
            return (string) file_get_contents(
                AddonApi::getInst()->getParam('dirs_pathes[js]') .
                'documentation.js'
            );
        }

        public function renderReports(): string {
            $res            = '';
            $v_report       = $this->getView('report');
            $v_report_group = $this->getView('report_group');
            foreach (AddonApi::getInst()->getParam('reports') as $group => $reports) {
                $v_report_group->group = $group;
                $res                  .= $v_report_group->loadTemplate();
                foreach ($reports as $code => $descr) {
                    $v_report->code  = $code;
                    $v_report->descr = $descr;
                    $res            .= $v_report->loadTemplate();
                }
            }
            return $res;
        }

        public function renderSectionsAndTasks(): string {
            $res                    = '';
            $v_task              = $this->getView('task');
            $v_task_param        = $this->getView('task_param');
            $v_task_params       = $this->getView('task_params');
            $v_task_param_short  = $this->getView('task_param_short');
            $v_task_params_short = $this->getView('task_params_short');
            $v_section           = $this->getView('section');
            foreach ($this->getSectionsAndTasks() as $section => $sdetails) {
                $tasks = '';
                foreach ($sdetails['tasks'] as $task => $tdetails) {
                    $params_short = '';
                    $params       = '';
                    if (isset($tdetails['params'])) {
                        foreach ($tdetails['params'] as $param => $pdetails) {
                            $v_task_param_short->default   = $pdetails['default'] ?? '—';
                            $v_task_param_short->param     = $param;
                            $v_task_param_short->type      = $pdetails['type'];
                            $params_short                 .= $v_task_param_short->loadTemplate();
                            $v_task_param->default         = $pdetails['default'] ?? '—';
                            $v_task_param->descr           = $pdetails['descr'];
                            $v_task_param->param           = $param;
                            $v_task_param->type            = $pdetails['type'];
                            $params                       .= $v_task_param->loadTemplate();
                        }
                        $v_task_params_short->params_short = $params_short;
                        $params_short                      = $v_task_params_short->loadTemplate();
                        $v_task_params->params             = $params;
                        $params                            = $v_task_params->loadTemplate();
                    }
                    $v_task->descr        = $tdetails['descr'];
                    $v_task->params       = $params       ? $params       : '';
                    $v_task->params_short = $params_short ? $params_short : '—';
                    $v_task->task         = $task;
                    $v_task->type         = $tdetails['type'];
                    $tasks               .= $v_task->loadTemplate();
                }
                $v_section->section = $section;
                $v_section->tasks   = $tasks;
                $res               .= $v_section->loadTemplate();
            }
            return $res;
        }

        public function renderSectionsAndTasksMenu(): string {
            $res  = '';
            $view = $this->getView('section_menu_item');
            foreach ($this->getSectionsAndTasks() as $section => $sdetails) {
                $view->section = $section;
                $res          .= $view->loadTemplate();
            }
            return $res;
        }

        public function renderVersionHistory(): string {
            $res     = '';
            $view_v  = $this->getView('version');
            $view_vc = $this->getView('version_change');
            foreach ($this->getVersionHistory() as $version) {
                $changes = '';
                foreach ($version['changes'] as $change) {
                    $view_vc->change = $change;
                    $changes        .= $view_vc->loadTemplate();
                }
                $view_v->date    = $version['date'];
                $view_v->version = $version['version'];
                $view_v->changes = $changes;
                $res            .= $view_v->loadTemplate();
            }
            return $res;
        }

        public function getView($name): JViewLegacy {
            return AddonApi::getInst()->getView('documentation/' . $name);
        }

    }
