<?php

namespace App\Service;


use App\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderHelper {

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger, string $postImageDirectory)
    {
        $this->slugger = $slugger;
        $this->postImageDirectory = $postImageDirectory;
    }

    public function uploadPostImage(Post $post, UploadedFile $uploadedFile)
    {
        // Si l'administrateur a rempli le champ image...
        if ($uploadedFile) {
            // Si un fichier image existe déjà (càd si le champ image de l'entité $post est rempli) ...
            if ($imageFilename = $post->getImage()) {

                // Suppression de l'image actuelle le cas échéant
                $this->removePostImageFile($post);
            }

            // Normalisatoin du nom du fichier image
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $this->slugger->slug($originalFilename). '-' .uniqid() . '.' . $uploadedFile->guessExtension();

            $post->setImage($newFilename);

            // Copie du fichier temporaire vers le répertoire de destination
            $uploadedFile->move($this->postImageDirectory, $newFilename);
        }
    }

    public function removePostImageFile(Post $post)
    {
        // Si un fichier (image existe déjà càd si le champ image de l'entité $post est rempli) ...
        if ($imageFilename = $post->getImage()) {

            // ... alors ou supprimer l'ancien fichier
            $currentPath = $this->postImageDirectory . '/' . $imageFilename;
            if ($this->filesystem->exists($currentPath)) {
                $this->filesystem->remove($currentPath);
            }
        }
    }
}