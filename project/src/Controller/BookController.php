<?php

namespace App\Controller;

use App\Form\AddBookType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Time;

class BookController extends AbstractController
{
	#[Route('/book', name: 'app_book')]
	public function index(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager): Response
	{

		$this->denyAccessUnlessGranted('ROLE_USER');

		$books = $bookRepository->findAll();

		$form = $this->createForm(AddBookType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$book = $form->getData();
			$entityManager->persist($book);
			$entityManager->flush();

			return $this->redirectToRoute('app_book');
		}


		return $this->render('book/index.html.twig', [
			'controller_name' => 'BookController',
			'books' => $books,
			'form' => $form,
		]);
	}


	#[Route('/book/{id}/delete', name: 'app_book_delete')]
	public function delete_book(int $id, EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
	{

		$this->denyAccessUnlessGranted('ROLE_USER');

		$book = $bookRepository->find($id);

		if ($book) {
			$entityManager->remove($book);
			$entityManager->flush();
		}

		return $this->redirectToRoute('app_book');
	}


	#[Route('/book/{id}/lu', name: 'app_book_lu')]
	public function read_book(int $id, EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
	{

		$this->denyAccessUnlessGranted('ROLE_USER');

		$book = $bookRepository->find($id);

		if ($book) {
			$book->setLu(!$book->isLu());
			$entityManager->flush();
		}

		return $this->redirectToRoute('app_book');
	}



	#[Route('/book/{id}/reading', name: 'app_book_reading')]
	public function reading_book(int $id, EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
	{

		$this->denyAccessUnlessGranted('ROLE_USER');

		$book = $bookRepository->find($id);
		$books = $bookRepository->findAll();

		if ($book) {

			foreach ($books as $_book) {
				if ($_book->isReading() == 1) {
					$_book_total_time = $_book->getTotalTime();
					$_book_start_time = $_book->getStartTime();

					$_time = $_book_total_time + (time() - $_book_start_time);
					$_book->setTotalTime($_time);
				}

				$_book->setReading(0);
			}

			$book->setReading(1);
			$book->setStartTime(time());

			$entityManager->flush();
		}

		return $this->redirectToRoute('app_book');
	}


	#[Route('/book/reading/stopall', name: 'app_book_reading_stop_all')]
	public function reading_stop_all_book(EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
	{

		$this->denyAccessUnlessGranted('ROLE_USER');

		$books = $bookRepository->findAll();

		foreach ($books as $_book) {
			if ($_book->isReading() == 1) {
				$_book_total_time = $_book->getTotalTime();
				$_book_start_time = $_book->getStartTime();

				$_time = $_book_total_time + (time() - $_book_start_time);
				$_book->setTotalTime($_time);
			}

			$_book->setReading(0);
		}

		$entityManager->flush();

		return $this->redirectToRoute('app_book');
	}



	#[Route('/book/reset', name: 'app_book_reset')]
	public function book_reset_time(EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
	{

		$books = $bookRepository->findAll();

		foreach ($books as $_book) {
			$_book->setReading(0);
			$_book->setStartTime(0);
			$_book->setTotalTime(0);
		}

		$entityManager->flush();

		return $this->redirectToRoute('app_book');
	}
}
