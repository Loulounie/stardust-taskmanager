<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tasks = $em->getRepository('AppBundle:Task')
        ->findAll();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $label = $request->request->get('label');

        $task = new Task();
        $task->setLabel($label);

        $em->persist($task);
        $em->flush();

        return $this->redirect( $this->generateUrl('homepage') );
    }

    /**
     * @Route("/delete", name="delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $taskId = $request->request->get('taskId');
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);

        $em->remove($task);
        $em->flush();

        return $this->redirect( $this->generateUrl('homepage') );
    }

    /**
     * @Route("/complete", name="complete")
     */
    public function completeAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $taskId = $request->request->get('taskId');
        
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);
        $task->setCompleted(True);
        
        $em->flush();

        return $this->redirect( $this->generateUrl('homepage') );
    }
}
