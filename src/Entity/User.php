<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="PlayerOne")
     */
    private $IsWhiteInGame;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="PlayerTwo")
     */
    private $IsBlackInGame;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;
    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;
    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $google_id;
    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $google_access_token;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $FirstLogin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="sender")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="receiver")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="author")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleAnswer", mappedBy="author")
     */
    private $articleAnswers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameOver", mappedBy="player")
     */
    private $gameOvers;

  
  /**
     * @ORM\OneToOne(targetEntity="App\Entity\Stats", cascade={"persist", "remove"})
     */
    private $stats;

   
  /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     **/
    private $friendsWithMe;

   
  /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     **/
    private $myFriends;
    

    public function __construct()
    {
        parent::__construct();
        $this->IsBlackInGame = new ArrayCollection();
        $this->FirstLogin = new \DateTime();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->IsWhiteInGame = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->articleAnswers = new ArrayCollection();
        $this->gameOvers = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();

    }

    public function isInGame()
    {
        if ($this->IsBlackInGame->isEmpty() && $this->IsWhiteInGame->isEmpty() ){
            return false;
        }
        return true;
    }

    public function getGames(){
        $games = [];
        if ($this->IsBlackInGame->isEmpty() && $this->IsWhiteInGame->isEmpty() ){
            return false;
        }
        foreach ($this->IsBlackInGame as $game) {
            $games[] = $game;
        }
        foreach ($this->IsWhiteInGame as $game){
            $games[] = $game;
        }
        return $games;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
       
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture): self
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }
    /**
     * @return Collection|Game[]
     */
    public function getIsWhiteInGame(): Collection
    {
        return $this->IsWhiteInGame;
    }

    public function addIsWhiteInGame(Game $isInGame): self
    {
        if (!$this->IsWhiteInGame->contains($IsWhiteInGame)) {
            $this->IsWhiteInGame[] = $IsWhiteInGame;
            $IsWhiteInGame->setPlayerOne($this);
        }

        return $this;
    }

    public function removeIsWhiteInGame(Game $IsWhiteInGame): self
    {
        if ($this->IsWhiteInGame->contains($IsWhiteInGame)) {
            $this->IsWhiteInGame->removeElement($IsWhiteInGame);
            // set the owning side to null (unless already changed)
            if ($IsWhiteInGame->getPlayerOne() === $this) {
                $IsWhiteInGame->setPlayerOne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getIsBlackInGame(): Collection
    {
        return $this->IsBlackInGame;
    }

    public function addIsBlackInGame(Game $isBlackInGame): self
    {
        if (!$this->IsBlackInGame->contains($isBlackInGame)) {
            $this->IsBlackInGame[] = $isBlackInGame;
            $isBlackInGame->setPlayerTwo($this);
        }

        return $this;
    }

    public function removeIsBlackInGame(Game $isBlackInGame): self
    {
        if ($this->IsBlackInGame->contains($isBlackInGame)) {
            $this->IsBlackInGame->removeElement($isBlackInGame);
            // set the owning side to null (unless already changed)
            if ($isBlackInGame->getPlayerTwo() === $this) {
                $isBlackInGame->setPlayerTwo(null);
            }
        }

        return $this;
    }

    public function getFirstLogin(): ?\DateTimeInterface
    {
        return $this->FirstLogin;
    }

    public function setFirstLogin(\DateTimeInterface $FirstLogin): self
    {
        $this->FirstLogin = $FirstLogin;

        return $this;
    }

    
    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    public function setFacebookId(?string $facebook_id): self
    {
        $this->facebook_id = $facebook_id;
        
        return $this;
    }


    public function getFacebookAccessToken(): ?string
    {
        return $this->facebook_access_token;
    }

    public function setFacebookAccessToken(?string $facebook_access_token): self
    {
        $this->facebook_access_token = $facebook_access_token;
      
        return $this;
    }
       
     
    public function getGoogleId(): ?string
    {
        return $this->google_id;
    }

    public function setGoogleId(?string $google_id): self
    {
        $this->google_id = $google_id;
      
        return $this;
    }
       
    public function getGoogleAccessToken(): ?string
    {
        return $this->google_access_token;
    }
       

    public function setGoogleAccessToken(?string $google_access_token): self
    {
        $this->google_access_token = $google_access_token;

        return $this;
    }
  
    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->removeElement($sentMessage);
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getReceiver() === $this) {
                $receivedMessage->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArticleAnswer[]
     */
    public function getArticleAnswers(): Collection
    {
        return $this->articleAnswers;
    }

    public function addArticleAnswer(ArticleAnswer $articleAnswer): self
    {
        if (!$this->articleAnswers->contains($articleAnswer)) {
            $this->articleAnswers[] = $articleAnswer;
            $articleAnswer->setAuthor($this);
        }

        return $this;
    }

    public function removeArticleAnswer(ArticleAnswer $articleAnswer): self
    {
        if ($this->articleAnswers->contains($articleAnswer)) {
            $this->articleAnswers->removeElement($articleAnswer);
            // set the owning side to null (unless already changed)
            if ($articleAnswer->getAuthor() === $this) {
                $articleAnswer->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameOver[]
     */
    public function getGameOvers(): Collection
    {
        return $this->gameOvers;
    }

    public function addGameOver(GameOver $gameOver): self
    {
        if (!$this->gameOvers->contains($gameOver)) {
            $this->gameOvers[] = $gameOver;
            $gameOver->setPlayer($this);
        }
    }

    /*
    * @return Collection|User[]
    */
    public function getMyFriends(): Collection
    {
        return $this->myFriends;
    }

    public function addMyFriend(User $myFriend): self
    {
        if (!$this->myFriends->contains($myFriend)) {
            $this->myFriends[] = $myFriend;

        }

        return $this;
    }


    public function removeGameOver(GameOver $gameOver): self
    {
        if ($this->gameOvers->contains($gameOver)) {
            $this->gameOvers->removeElement($gameOver);
            // set the owning side to null (unless already changed)
            if ($gameOver->getPlayer() === $this) {
                $gameOver->setPlayer(null);
            }
        }
    }

    public function removeMyFriend(User $myFriend): self
    {
        if ($this->myFriends->contains($myFriend)) {
            $this->myFriends->removeElement($myFriend);

        }

        return $this;
    }


    public function getStats(): ?Stats
    {
        return $this->stats;
    }

    public function setStats(?Stats $stats): self
    {
        $this->stats = $stats;

        return $this;
    }

}



       
       
   