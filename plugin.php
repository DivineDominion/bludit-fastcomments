<?php defined('BLUDIT') or die('Bludit CMS.');

class FastCommentsPlugin  {
  const DEFAULT_POSITION = 'pageEnd';

  public function init(){
    $this->dbFields = array(
      'position' => self::DEFAULT_POSITION,
      'tenant_id' => '',
    );
  }

  private function commentPosition(): string {
    return $this->getValue('position') ?? self::DEFAULT_POSITION;
  }

  private function tenantID(): string {
    $result = $this->getValue('tenant_id') ?? '';
    return trim($result);
  }

  /** Method called on the settings of the plugin on the admin area. */
  public function form()
  {
    global $L;

    $html  = '<div class="alert alert-primary" role="alert">';
    $html .= $this->description();
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<label>'.$L->get('FastComments Tenant ID').'</label>';
    $html .= '<input name="forum_url" value="'.$this->tenantID().'">';
    $html .= '<span class="tip">'.$L->get('The FastComments account identifier.').'</span>';
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<label>'.$L->get('Position').'</label>';
    $html .= '<select name="position">';
    $html .= '<option value="disabled" '.     ($this->getValue('position')==='disabled'?'selected':'').'>'.$L->get('Disabled').'</option>';
    $html .= '<option value="siteBodyBegin" '.($this->getValue('position')==='siteBodyBegin'?'selected':'').'>'.$L->get('siteBodyBegin').'</option>';
    $html .= '<option value="pageBegin" '.    ($this->getValue('position')==='pageBegin'?'selected':'').'>'.$L->get('pageBegin').'</option>';
    $html .= '<option value="pageEnd" '.      ($this->getValue('position')==='pageEnd'?'selected':'').'>'.$L->get('pageEnd').'</option>';
    $html .= '<option value="siteBodyEnd" '.  ($this->getValue('position')==='siteBodyEnd'?'selected':'').'>'.$L->get('siteBodyEnd').'</option>';
    $html .= '</select>';
    $html .= '<span class="tip">'.$L->get('Where to show the page comments, if enabled.').'</span>';
    $html .= '</div>';

    return $html;
  }

  public function siteBodyBegin() {
    if ($this->commentPosition() !== "siteBodyBegin") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function pageBegin() {
    if ($this->commentPosition() !== "pageBegin") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function pageEnd() {
    if ($this->commentPosition() !== "pageEnd") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function siteBodyEnd() {
    if ($this->commentPosition() !== "siteBodyEnd") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  private function renderComments() {
    global $page;
    global $url;

    if ($url->whereAmI() !== 'page') {
      return false;
    }

    $tenantID = $this->tenantID();
    if (!$page->allowComments() || $tenantID === '') {
      return false;
    }

    ob_start();
    ?>
      <script src="https://cdn.fastcomments.com/js/embed-v2.min.js"></script>
      <div id="fastcomments-widget"></div>
      <script>FastCommentsUI(document.getElementById('fastcomments-widget'), {"tenantId": "<?=$tenantID?>"});</script>
      <noscript>Please enable JavaScript to use the comment system.</noscript>
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }
}
