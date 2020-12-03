<?php


namespace App\Controller;


use App\Entity\Item;
use App\Service\ItemCreation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/item", name="item_")
 */
class ItemController extends AbstractController
{

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * ItemController constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->em = $em;
    }


    /**
     * @Route("", name="select", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function select(Request $request): Response
    {
        var_dump($request->getClientIp());
        $getPrivate = $request->query->has("private");
        $items = array_map(function (Item $item) {
            return $item->serialize();
        }, $getPrivate ?
            $this->em->getRepository(Item::class)->findPrivate() :
            $this->em->getRepository(Item::class)->findPublic()
        );
        return new Response(json_encode($items));
    }

    /**
     * @Route("", name="insert", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function insert(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        if (!is_array($content)) {
            return new Response("KO");
        }
        $errors = array();
        foreach ($content as $itemContent) {
            $itemCreation = new ItemCreation($itemContent);
            $violations = $this->validator->validate($itemCreation);
            if (count($violations) > 0) {
                $error = array();
                for ($i = 0; $i < $violations->count(); $i++) {
                    $error[] = $violations->get($i)->getMessage();
                }
                $errors[] = $error;
                continue;
            }
            $this->em->persist($itemCreation->getItem());
        }
        $this->em->flush();
        if (count($errors) > 0) {
            return new Response(json_encode($errors));
        }
        return new Response("OK");
    }

}