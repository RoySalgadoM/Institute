<?php

namespace App\Controller;
header('Access-Control-Allow-Origin: *');

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SchoolController extends AbstractController
{
    
    public function findAllSchools()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em -> createQuery('SELECT s.id, s.name, s.street, s.created, s.updated, s.status FROM App:School s');
        $listSchools = $query -> getResult();
        $data = [
            'status'=> 404,
            'message'=>'No se encontraron resultados.'
        ];
        if(count($listSchools) > 0){
            $data = [
                'status'=> 200,
                'message'=>'Se encontraron '. count($listSchools).' resultados.',
                'listSchools' => $listSchools
            ];
        }
        return new JsonResponse($data);
    }
    public function findProductById($id){
        $em = $this->getDoctrine()->getManager();
        $query = $em -> createQuery('SELECT s.id, s.name, s.street, s.created, s.updated, s.status FROM App:School s WHERE s.id=:p');
        $query->setParameter(':p',$id);
        $school = $query->getResult();
        $data = [
            'status'=> 404,
            'message'=>'No se ha encontrado la escuela'
        ];
        if(count($school) > 0){
            $data = [
                'status'=> 200,
                'message'=>'Se ha encontrado la escuela',
                'school' => $school
            ];
        }
        return new JsonResponse($data);
    }
    public function createSchool(Request $request){
        $em = $this->getDoctrine()->getManager();

        $name = $request->get('name',null);
        $street = $request->get('street',null);
        $status = $request->get('status',null);
        $school = new \App\Entity\School();
        $school->setName($name);
        $school->setStreet($street);
        $school->setStatus($status);
        $dataTime = new DateTime('NOW');
        $school->setCreated($dataTime);
        $school->setUpdated($dataTime);

        $em -> persist($school);
        $em ->flush();

        $data = [
            'status'=> 200,
            'message'=>'Se ha creado correctamente'
        ];
        return new JsonResponse($data);
    }

    public function updateSchool(Request $request){
        $em = $this->getDoctrine()->getManager();

        $name = $request->get('name',null);
        $street = $request->get('street',null);
        $status = $request->get('status',null);
        $id = $request->get('id',null);

        $query = $em -> createQuery('UPDATE App:School s SET s.name = :name, s.status= :status, s.street=:street, s.updated=:updated WHERE s.id=:p');
        $query->setParameter(':name',$name);
        $query->setParameter(':status',$status);
        $query->setParameter(':street',$street);
        $dateTime = new DateTime('NOW');
        $query->setParameter(':updated',$dateTime);
        $query->setParameter(':p',$id);
        $flag = $query->getResult();

        if($flag ==1){
            $data = [
                'status'=> 200,
                'message'=>'Se ha modificado correctamente'
            ];
        }else{
            $data = [
                'status'=> 404,
                'message'=>'No se ha modificado correctamente'
            ];
        }
        return new JsonResponse($data);
    }

    public function deleteSchool($id){
        $em = $this->getDoctrine()->getManager();
        $query = $em -> createQuery('DELETE FROM App:School s WHERE s.id =:p');
        $query->setParameter(':p',$id);
        $flag = $query->getResult();
        if($flag==1){
            $data = [
                'status'=> 200,
                'message'=>'Se ha eliminado correctamente'
            ];
        }else{
            $data = [
                'status'=> 200,
                'message'=>'No se ha eliminado correctamente'
            ];
        }
        return new JsonResponse($data);
    }
}
