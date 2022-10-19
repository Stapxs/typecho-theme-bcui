<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/comments.css'); ?>">

<div id="comments">
    <!-- <div class="comment-bar"></div> -->
    <?php $this->comments()->to($comments); ?>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond comment-respond">
            <!-- <div class="comment-respond-top">
                <div class="space"></div>
                <div class="show"></div>
                <div class="middle"></div>
                <div class="show"></div>
                <div class="space"></div>
            </div> -->

            <div class="cancel-comment-reply">
                <?php $comments->cancelReply(); ?>
            </div>
            
            <div class="ss-card comment-respond-body">
                <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
                    <?php if ($this->user->hasLogin()): ?>
                        <div class="ss-card comment-login-info login">
                            <span>评论板</span>
                            <div>
                                <a class="name" href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
                                <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?></a>
                            </div>
                            <?php echo $this->user->gravatar(250, 'G', NULL, NULL) ?>
                        </div>
                    <?php else: ?>
                        <div class="comment-login-info unlogin">
                            <div>
                                <p>
                                    <input type="text" name="author" id="author" class="text" placeholder="<?php _e('称呼'); ?>"
                                           value="<?php $this->remember('author'); ?>" required/>
                                </p>
                                <p>
                                    <input type="email" name="mail" id="mail" class="text"  placeholder="<?php _e('Email'); ?>"
                                           value="<?php $this->remember('mail'); ?>"
                                           <?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
                                </p>
                                <p>
                                    <input type="url" name="url" id="url" class="text" placeholder="<?php _e('网站(http://)'); ?>"
                                           value="<?php $this->remember('url'); ?>"
                                           <?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <p>
                        <label for="textarea" class="required"><?php _e('内容'); ?></label>
                        <textarea rows="8" cols="50" name="text" id="textarea" class="textarea"
                                  required><?php $this->remember('text'); ?></textarea>
                    </p>
                    <p>
                        <button type="submit" class="submit ss-button"><?php _e('提交评论'); ?></button>
                    </p>
                </form>
            </div>
        </div>
    <?php else: ?>
        <h3><?php _e('评论已关闭'); ?></h3>
    <?php endif; ?>
</div>
