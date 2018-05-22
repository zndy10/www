<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:45:"./template/pc/rainbow/goods\ajax_consult.html";i:1522317287;}*/ ?>
<div class="mes-num-h">共<span class="c-orange consult_count"><?php echo $consultCount; ?></span>条</div>
<?php if(is_array($consultList) || $consultList instanceof \think\Collection || $consultList instanceof \think\Paginator): if( count($consultList)==0 ) : echo "" ;else: foreach($consultList as $key=>$consult): ?>
    <div class="mes-lists-h">
        <div class="mes-item1-h">
            <div class="visitor-mes p">
                <span class="visitor-name"><?php echo $consult['username']; ?>:</span><?php echo $consult['content']; ?><p class="send-time"><?php echo date('Y-m-d H:i',$consult['add_time']); ?></p>
            </div>
            <?php if(is_array($consult['replyList']) || $consult['replyList'] instanceof \think\Collection || $consult['replyList'] instanceof \think\Paginator): if( count($consult['replyList'])==0 ) : echo "" ;else: foreach($consult['replyList'] as $key=>$reply): ?>
                <div class="store-reply p">
                    <span class="visitor-name">商家<i class="reply-tips">回复</i><?php echo $reply['username']; ?>:</span><?php echo $reply['content']; ?>
                    <p class="send-time"><?php echo date('Y-m-d H:i',$reply['add_time']); ?></p>
                </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>
<?php echo $page; ?>