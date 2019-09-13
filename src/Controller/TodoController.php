<?php

namespace App\Controller;

use App\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\CreateTodoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends AbstractController {

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $todoRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->todoRepository = $entityManager->getRepository('App:Todo');
    }

    /**
     * @Route("/", name="todo_show")
     */
    public function show(Request $request) {
        $todo = new Todo();
        $entries = array();

        // add empty form
        $form = $this->createForm(CreateTodoType::class, $todo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $this->entityManager->persist($todo);
            $this->entityManager->flush($todo);
        }

        // load all todos + add delete button
        if ($this->todoRepository->findAll()) {
            foreach ($this->todoRepository->findAll() as $item) {
                $deleteForm = $this->createDeleteForm($item);
                $entries[] = array('item' => $item, 'delete' => $deleteForm->createView());
            }
        }

        return $this->render('todo/show.html.twig', [
                    'form' => $form->createView(),
                    'entries' => $entries
        ]);
    }

    /**
     * @Route("/tododelete/{id}", name="todo_delete" , requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, $id) {
        $todo = $this->todoRepository->find($id);

        $this->entityManager->remove($todo);
        $this->entityManager->flush();
        return $this->redirectToRoute('todo_show');
    }

    /**
     *
     * @param Todo $todo
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
                        ->add('delete', SubmitType::class, ['label' => 'Verwijder'])
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
