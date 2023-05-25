<?php
require_once('../../src/model/Database.php');
require_once('../../src/model/Pages.php');
// require_once('../../src/controller/pagesController.php')
// Connecter la BDD
$db = new Database();
// Ouverture de la connection
$connection = $db->getConnection();
// Requêtes SQL
$name = null;

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    setcookie("name", $name);
} else {
    echo "Name parameter not provided!";
}

$pages = new Pages();

[$page, $idPage, $namePage, $iconProfile, $bannerProfile] = $pages->setPage($name, $connection);

$postCount = null;
$posts = null;


?>
<?php include '../components/header.php' ?>
<link rel="stylesheet" href="../styles/profile.css">
<link rel="stylesheet" href="../styles/publication.css">


<div class="banner">
    <img class="banner-img" src=<?= $bannerProfile ?>>
    <input type="submit" class="Submitbutton" value="Modifier le profil">
    <div class="page_info">
        <div class="profile-img">
            <img src=<?= $iconProfile ?> alt="iconProfile">
        </div>
        <div class="profile-nom-prenom">
            <h3><span class="white_space"><?= $namePage ?></span></h3>
            <h4><span class="white_space"><?= $postCount ?> posts</span></h4>
        </div>
    </div>
</div>

<div class="summary">
    <h3>
        <a class="summary-link profile_publication" href="#" data-target="box_main">Publications</a>
    </h3>

    <h3>
        <a class="summary-link profile_aboutus" href="#" data-target="box_aboutus">À propos</a>
    </h3>

    <h3>
        <a class="summary-link profile_friends" href="#" data-target="box_summary_friends">Abonnés</a>
    </h3>

    <h3>
        <a class="summary-link profile_photos" href="#" data-target="box_summary_photos">Photos</a>
    </h3>
</div>

<div class="summary-content box_main" id="box_main">

    <div class="box_left">

        <div class="box_friends">
            <div class="box-title">
                <h2>Abonnés</h2>
                <a class="profile_friends_link" href="#">Tous les abonnés</a>
            </div>

            <div class="box-img">

                <div class="friends-info">
                    <div class="friends_info_pp">
                        <img src="./img/pp2.png" class="box_photos_friend_picture">
                    </div>
                    <p>Pseudo</p>
                </div>

                <div class="friends-info">
                    <div class="friends_info_pp">
                        <img src="./img/pp2.png" class="box_photos_friend_picture">
                    </div>
                    <p>Pseudo</p>
                </div>

                <div class="friends-info">
                    <div class="friends_info_pp">
                        <img src="./img/pp2.png" class="box_photos_friend_picture">
                    </div>
                    <p>Pseudo</p>
                </div>

            </div>

        </div>

        <div class="box_photos">
            <div class="box-title">
                <h2>Photos</h2>
                <a class="profile_photos_link" href="#">Toutes les photos</a>
            </div>
            <div class="box-img">

                <?php
                [$posts, $postCount] = $pages->fetchPublication($idPage, $connection);
                foreach ($posts as $post) :
                    [$idPublication, $description, $image] = $pages->Publication($post, $connection); ?>
                    <?php if ($image !== "") : ?>
                        <img src="<?= $image ?>" class="box_photos_picture">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="list_publications">

        <div class="profile_publication_post">
            <div class="profile_publication_div_flex">
                <div class="publication_pp_div">
                    <img src=<?= $iconProfile ?> alt="profile_picture">
                </div>
                <div class="profile_publication_div_post">
                    <textarea class="publication_person_comment_input" maxlength="500" placeholder="Que voulez-vous dire ?" oninput="autoResize(this)"></textarea>
                </div>
            </div>

            <div class="group_preview_publication_image">
                <label id="custom-img-btn">
                    <div class="group_preview_publication_sub">
                        <span class="material-icons">image</span>
                        <p>Photo</p>
                    </div>
                </label>

                <label id="custom-video-btn">
                    <div class="group_preview_publication_sub">
                        <span class="material-icons">videocam</span>
                        <p>Vidéo</p>
                    </div>
                </label>

                <div class="btn_send">
                    <a href="#" id="send"><span class="material-icons chat_send">send</span></a>
                </div>

            </div>

            <div id="publication_image">
                <button class="remove_btn"><span class="material-icons-round">close</span></button>
            </div>

        </div>

        <?php 
        [$posts, $postCount] = $pages->fetchPublication($idPage, $connection);
        foreach ($posts as $post) :
            [$idPublication, $description, $image, $usersPostsLikesCount, $count] = $pages->Publication($post, $connection);?>
            <div class="publication">

                <div class="publication_info">
                    <div class="publication_pp_div">
                        <img src=<?= $iconProfile ?> alt="">
                    </div>
                    <div>
                        <p><?= $name ?></p>
                    </div>
                </div>


                <p><?= $description ?></p>

                <div class="publication_list_images">
                    <?php if ($image !== "") : ?>
                        <img src=<?= $image ?> alt="" class="publication_image">
                    <?php endif; ?>
                </div>

                <div class="publication_post_info">
                    <p><?= $usersPostsLikesCount ?> personnes ont aimés</p>
                    <p><?= $count ?> commentaires</p>
                </div>

                <div class="publication_post_reaction">
                    <div class="group_preview_publication_sub">
                        <span class="material-icons">thumb_up</span>
                        <p>J'aime</p>
                    </div>
                    <div class="group_preview_publication_sub">
                        <span class="material-icons">add_comment</span>
                        <p>Commenter</p>
                    </div>
                    <div class="group_preview_publication_sub">
                        <span class="material-icons">send</span>
                        <p>Envoyer</p>
                    </div>
                </div>


                <div class="publication_list_comments">

                    <!-- commentaire qui se répond a un autre -->
                    <?php
                    [$postsComs, $postComCount] = $pages->fetchCommentary($idPublication, $connection);
                    foreach ($postsComs as $post) :
                        [$username, $profile_pic, $description, $idCommentaire, $timestamp] = $pages->Commentary($post, $connection);
                        if ($username): ?>
                    <div class="publication_comment">

                        <div class="publication_info">
                            <div class="publication_pp_div">
                            <img src=<?= $profile_pic ?> alt="">
                        </div>
                    </div>

                        <div>
                            <div class="publication_person_comment">
                                <p class="publication_name"><?= $username ?></p>
                                <p><?= $description ?></p>
                            </div>

                            <div class="publication_person_comment_options_reaction">
                                <div class="publication_person_comment_options">
                                    <p>J'aime</p>
                                    <p>Répondre</p>
                                    <p><?= $timestamp ?> h</p>
                                </div>

                                <div class="publication_comment_reaction">
                                    <span class="material-icons">thumb_up</span>
                                    <p>1000</p>
                                </div>
                            </div>


                            <div>
                                <?php [$postsComs2, $postComCount2] = $pages->fetchCommentary2($idCommentaire, $connection);
                                foreach ($postsComs2 as $post):
                                    [$username, $profile_pic, $description, $timestamp] = $pages->Commentary2($post, $connection); ?>
                                <div class="publication_comment">
                                    <div class="publication_info">
                                        <div class="publication_pp_div">
                                            <img src=<?= $profile_pic ?> alt="">
                                        </div>
                                    </div>

                                    <div>
                                        <div class="publication_person_comment">
                                            <p class="publication_name"><?= $username ?></p>
                                            <p><?= $description ?></p>
                                        </div>

                                        <div class="publication_person_comment_options_reaction">
                                            <div class="publication_person_comment_options">
                                                <p>J'aime</p>
                                                <p>Répondre</p>
                                                <p><?= $timestamp ?> h</p>
                                            </div>

                                            <div class="publication_comment_reaction">
                                                <span class="material-icons">thumb_up</span>
                                                <p>1000</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>

                        </div>
                    </div>
                    <?php endif ?>
                    <?php endforeach; ?>
                    <!-- écrire un commentaire -->
                </div>

                <div>
                    <div class="publication_comment">
                        <div class="publication_info">
                            <div class="publication_pp_div">
                                <img src="./img/pp.png" alt="">
                            </div>
                        </div>

                        <div class="publication_person_comment">

                            <textarea class="publication_person_comment_input" maxlength="300" placeholder="Ecrire un commentaire..." oninput="autoResize(this)"></textarea>
                            <div class="publication_person_emoji_react">
                                <div>
                                    <span class="material-icons-outlined">mood</span>
                                    <span class="material-icons-outlined">gif</span>
                                </div>
                                <span class="material-icons">send</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
        <!-- fin publication -->

    </div>

</div>

<a href="profile.php" class="summary-content box_return">
    <span class="material-icons-outlined material-icons-round return_icon">arrow_back</span>
    <p>Retour</p>
</a>

<div class="summary-content box_aboutus">

    <div class="box-title">
        <h2>À propos</h2>
    </div>

    <div class="user-info">

        <div class="box_aboutus_info">
            <div class="logo-info">
                <span class="material-icons-outlined md-20">location_on</span>
                <p id="user-info-list">Habite à X</p>
            </div>
            <div class="box_aboutus_edits">
                <span class="material-icons-outlined md-20">public</span>
                <span class="material-icons-outlined md-20">edit</span>
                <span class="material-icons-outlined md-20">delete</span>
            </div>
        </div>

        <div class="box_aboutus_info">
            <div class="logo-info">
                <span class="material-icons-outlined md-20">home_repair_service</span>
                <p id="user-info-list">Travaille à X</p>
            </div>
            <div class="box_aboutus_edits">
                <span class="material-icons-outlined md-20">public</span>
                <span class="material-icons-outlined md-20">edit</span>
                <span class="material-icons-outlined md-20">delete</span>
            </div>
        </div>

        <div class="box_aboutus_info">
            <div class="logo-info">
                <span class="material-icons-outlined md-20">school</span>
                <p id="user-info-list">À étudié(e) au lycée Machin truc</p>
            </div>
            <div class="box_aboutus_edits">
                <span class="material-icons-outlined md-20">public</span>
                <span class="material-icons-outlined md-20">edit</span>
                <span class="material-icons-outlined md-20">delete</span>
            </div>
        </div>

        <div class="box_aboutus_info">
            <div class="logo-info">
                <span class="material-icons-outlined md-20">favorite</span>
                <p id="user-info-list">Célibataire</p>
            </div>
            <div class="box_aboutus_edits">
                <span class="material-icons-outlined md-20">public</span>
                <span class="material-icons-outlined md-20">edit</span>
                <span class="material-icons-outlined md-20">delete</span>
            </div>
        </div>

        <div class="box_aboutus_info">
            <div class="logo-info">
                <span class="material-icons-outlined md-20">mail</span>
                <p id="user-info-list">machin@machin.fr</p>
            </div>
            <div class="box_aboutus_edits">
                <span class="material-icons-outlined md-20">public</span>
                <span class="material-icons-outlined md-20">edit</span>
                <span class="material-icons-outlined md-20">delete</span>
            </div>
        </div>
    </div>


</div>


<div class="summary-content box_summary_friends">

    <div class="box_friends_title">
        <h2>Abonnés</h2>
        <input type="text" name="" id="" placeholder="Rechercher un ami..." class="box_friends_research" maxlength="75">
    </div>

    <div class="box_friends_info">

        <div class="box_friends_friend">
            <div class="box_friends_pp_name">
                <div class="friends_info_pp">
                    <img src="./img/pp2.png" class="box_photos_friend_picture">
                </div>
                <div class="box_friends_name">
                    <p>Nom Prénom</p>
                </div>
            </div>
            <span class="material-icons md-20">person_remove</span>
        </div>

        <div class="box_friends_friend">
            <div class="box_friends_pp_name">
                <div class="friends_info_pp">
                    <img src="./img/pp2.png" class="box_photos_friend_picture">
                </div>
                <div class="box_friends_name">
                    <p>Nom Prénom</p>
                </div>
            </div>
            <span class="material-icons md-20">person_remove</span>
        </div>

        <div class="box_friends_friend">
            <div class="box_friends_pp_name">
                <div class="friends_info_pp">
                    <img src="./img/pp2.png" class="box_photos_friend_picture">
                </div>
                <div class="box_friends_name">
                    <p>Nom Prénom</p>
                </div>
            </div>
            <span class="material-icons md-20">person_remove</span>
        </div>

    </div>


</div>


<div class="summary-content box_summary_photos">

    <div class="box-title">
        <h2>Photos</h2>
    </div>

    <div class="box_photos_all">

        <div class="friends_info_pp">
            <img src="./img/pp2.png" class="box_photos_friend_picture">
        </div>
        <div class="friends_info_pp">
            <img src="./img/pp2.png" class="box_photos_friend_picture">
        </div>
        <div class="friends_info_pp">
            <img src="./img/pp2.png" class="box_photos_friend_picture">
        </div>
        <div class="friends_info_pp">
            <img src="./img/pp2.png" class="box_photos_friend_picture">
        </div>
        <div class="friends_info_pp">
            <img src="./img/pp2.png" class="box_photos_friend_picture">
        </div>

    </div>


</div>

<script src="./scripts/script_profile.js"></script>
<script src="./scripts/script.js"></script>
<script src="./scripts/script_publication.js"></script>

<?php
include '../components/footer.php'
?>