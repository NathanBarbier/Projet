<?php
session_start();

require_once 'constants.php';
require_once 'globalFunctions.php';
require_once MODELS_PATH.'Modele.php';
require_once MODELS_PATH.'AllowedIp.php';
require_once MODELS_PATH.'BannedIp.php';
require_once MODELS_PATH.'Team.php';
require_once MODELS_PATH.'Organization.php';
require_once MODELS_PATH.'Project.php';
require_once MODELS_PATH.'User.php';
require_once MODELS_PATH.'BelongsTo.php';
require_once MODELS_PATH.'Task.php';
require_once MODELS_PATH.'MapColumn.php';
require_once MODELS_PATH.'TaskComment.php';
require_once MODELS_PATH.'TaskMember.php';
require_once MODELS_PATH.'LogHistory.php';
require_once REPO_PATH.'Repository.php';
require_once REPO_PATH.'UserRepository.php';
require_once REPO_PATH.'ProjectRepository.php';
require_once REPO_PATH.'TeamRepository.php';
require_once REPO_PATH.'MapColumnRepository.php';

?>