<?php

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;
use InvalidArgumentException;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class WriteController extends AbstractActionController
{
    private PostCommandInterface $command;

    private PostForm $form;

    private PostRepositoryInterface $repository;

    /**
     * @param PostCommandInterface $command
     * @param PostForm $form
     */
    public function __construct(PostCommandInterface $command, PostForm $form, PostRepositoryInterface $repository)
    {
        $this->command = $command;
        $this->form = $form;
        $this->repository = $repository;
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ["form" => $this->form];
        }

        $this->form->setData($request->getPost());

        if (!$this->form->isValid()) {
            return ["form" => $this->form];
        }

        $post = $this->form->getData();

        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }

        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }

        try {
            $post = $this->repository->findPost($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }

        $this->form->bind($post);
        $viewModel = new ViewModel(['form' => $this->form]);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());

        if (!$this->form->isValid()) {
            return $viewModel;
        }

        $post = $this->command->updatePost($post);
        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }
}
