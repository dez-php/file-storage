<?php

namespace FileStorage\Controllers;

use Dez\Authorizer\Models\Auth\TokenModel;
use Dez\Html\Element\AElement;
use Dez\Http\Response;
use FileStorage\Core\Mvc\ControllerWeb;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;
use FileStorage\Services\Emoji;
use FileStorage\Services\Uploader\Mimes;
use FileStorage\Services\Uploader\Uploader;

class ManagerController extends ControllerWeb
{

    public function beforeExecute()
    {
        parent::beforeExecute();

        if ($this->getAction() !== 'index' && $this->authorizerSession->isGuest()) {
            $this->response->redirect($this->url->path('manager/index'))->send();
        }

        $this->view->set('session_progress_name', ini_get('session.upload_progress.name'));
    }

    public function usersAction($subAction = 'index')
    {
        $this->view->set('content', $this->execute([
            'namespace' => 'FileStorage\\Controllers\\SubControllers\\',
            'controller' => 'users',
            'action' => $subAction
        ], true));
    }

    public function filesAction($subAction = 'index')
    {
        $this->view->set('content', $this->execute([
            'namespace' => 'FileStorage\\Controllers\\SubControllers\\',
            'controller' => 'files',
            'action' => $subAction
        ], true));
    }

    public function uploadFileAction()
    {
        /** @var TokenModel $token */
        $token = $this->authorizerToken->getModel()
            ->query()
            ->where('unique_hash', $this->authorizerSession->getModel()->getUniqueHash())
            ->where('auth_id', $this->authorizerSession->credentials()->id())
            ->first();

        if(! $token->exists()) {
            $link = new AElement($this->url->path('manager/users/profile'), 'Generate token');
            $this->flash->warning("{$link} before access this page");
            $this->redirect('manager/users/profile');
        } else {
            $this->view->set('token', $token->getToken());
            $this->view->set('categories', Categories::owned($this->authId())->find());
        }

    }

    public function categoriesAction()
    {
        $this->view->set('categories', Categories::owned($this->authId())->find());
    }

    public function createCategoryAction()
    {
        if ($this->request->isPost()) {
            $category = new Categories();
            $category->setName($this->request->getPost('name'));
            $category->setUserId($this->authId());
            $category->save();
            $this->flash->info("Category #{$category->id()} was created");
        }

        $this->redirect('manager/categories');
    }

    public function deleteCategoryAction($id)
    {
        $category = Categories::one($id);
        $category->deactivate();

        $this->flash->warning("Category #{$category->id()} was deleted");
        $this->redirect('manager/categories');
    }

    public function activateCategoryAction($id)
    {
        $category = Categories::one($id);
        $category->activate();

        $this->flash->notice("Category #{$category->id()} was activated");
        $this->redirect('manager/categories');
    }

    public function latestAction($limit = 100)
    {
        $latest = Files::latest($limit);
        $this->view->set('latest', $latest->find());
    }

    public function dashboardAction()
    {
        $this->response->redirect($this->url->path('manager/files/index'))->send();
    }

    public function serverInfoAction()
    {
        $this->view->set('os', php_uname());
        $this->view->set('php_version', PHP_VERSION);
        $this->view->set('sapi', $this->request->getServer('server_software'));

        $this->view->set('free_disk_space', Uploader::humanizeSize(disk_free_space('.')));

        $this->view->set('upload_max_filesize',
            Uploader::humanizeSize(Uploader::byteSize(ini_get('upload_max_filesize'))));
        $this->view->set('post_max_size', Uploader::humanizeSize(Uploader::byteSize(ini_get('post_max_size'))));

        $publicDirectory = realpath($this->config->path('application.uploader.directories.public'));
        $privateDirectory = realpath($this->config->path('application.uploader.directories.private'));

        $this->view->set('public_directory', $publicDirectory);
        $this->view->set('private_directory', $privateDirectory);
        $this->view->set('free_disk_space_public', Uploader::humanizeSize(disk_free_space($publicDirectory)));
        $this->view->set('free_disk_space_private', Uploader::humanizeSize(disk_free_space($privateDirectory)));

        $sizes = $this->config->path('application.uploader.validation.sizes');
        $this->view->set('validation_min_size', Uploader::humanizeSize($sizes->get('min')));
        $this->view->set('validation_max_size', Uploader::humanizeSize($sizes->get('max')));
        $this->view->set('validation_mimes', $this->config->path('application.uploader.validation.mimes'));
        $this->view->set('validation_extensions', $this->config->path('application.uploader.validation.extensions'));

        $this->view->set('uploaded_files', Files::all()->count());

    }

    public function indexAction()
    {
        $this->view->setMainLayout('auth-index');
        if ($this->request->isPost()) {
            try {
                $this->authorizerSession
                    ->setEmail($this->request->getPost('email'))
                    ->setPassword($this->request->getPost('password'))
                    ->login();
                $this->flash->success('Welcome on board');
            } catch (\Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
            $this->response->redirect($this->url->path('manager/index'))->send();
        }
    }

    public function closeSessionAction()
    {
        $this->authorizerSession->logout();
        $this->flash->notice('You are logged out');
        $this->response->redirect($this->url->path('manager/index'))->send();
    }

    public function generateFaviconAction()
    {
        $this->response->setBodyFormat(Response::RESPONSE_RAW);
        $this->response->setContentType(Mimes::mime('png'));

        $image = new \Imagick();
        $draw = new \ImagickDraw();

        $image->newImage(32, 32, new \ImagickPixel('#FF4500'));

        $draw->setFillColor(new \ImagickPixel('#000000'));
        $draw->setFontSize(22);

        $image->annotateImage($draw, 2, 23, 0, 'FS');
        $image->setImageFormat('png');

        echo $image;
    }

    public function emojiTestAction()
    {
        $reflaction = new \ReflectionClass(Emoji::class);
        $constants = $reflaction->getConstants();

        $this->view->set('constants', $constants);
    }

}