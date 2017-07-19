<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;


class DefaultController extends Controller
{
    /**
     * @Route("/{page}", name="homepage", requirements={"page": "\d+"}, defaults={"page": "1"})
     */
    public function indexAction(Request $request, $page)
    {
        $offset = ($page-1)*5;
        $em = $this->getDoctrine()->getEntityManager();
        $tasks = $em->getRepository('AppBundle:Task')
        ->findBy(array(), array('id' => 'ASC'), 5, $offset);

        //finds the last page
        $queryBuilder = $em->getRepository('AppBundle:Task')->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a)');
        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $last = ceil($count/5);

        if($page > $last)
        {
            return $this->redirect( $this->generateUrl('homepage') );
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tasks' => $tasks, 'page' => $page, 'last' => $last
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

        $this->addFlash(
            'notice',
            'Task succesfully added!'
        );
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
        $this->addFlash(
            'notice',
            'Task succesfully deleted!'
        );
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
        $this->addFlash(
            'notice',
            'Task succesfully completed!'
        );
        return $this->redirect( $this->generateUrl('homepage') );
    }
}
