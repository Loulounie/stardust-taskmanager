<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;


class TaskController extends Controller
{
    /**
     * @Route("/task/{id}", name="taskpage", requirements={"id": "\d+"})
     */
    public function viewAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();

        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($id);
        
        $em->flush();

        if(!$task)
        {
        	throw $this->createNotFoundException('This task does not exist');
        }
        return $this->render('default/task.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'task' => $task
        ]);
    }


    /**
     * @Route("/task/complete", name="taskcomplete")
     */
    public function completeAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $taskId = $request->request->get('taskId');
        
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($taskId);
        $task->setCompleted(True);
        
        $em->flush();
        $this->addFlash(
            'notice',
            'Task succesfully completed!'
        );
        return $this->redirect($this->generateUrl('homepage') . "task/" . $taskId);
    }
}