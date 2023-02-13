<template>
  <footer>
    <div>
      <div class="footerTitle">
        <a href="#">
          Challenge
        </a>
      </div>
      <div class="section">
        <p>Links</p>
        <ul>
          <li>
            <a href="#">FAQ</a>
          </li>
          <li>
            <a href="#">Help</a>
          </li>
          <li>
            <a href="#">Support</a>
          </li>
        </ul>
      </div>
      <div class="section">
        <p>Legal</p>
        <ul>
          <li>
            <a href="#">Terms</a>
          </li>
          <li>
            <a href="#">Privacy</a>
          </li>
          <li>
            <a href="#">Cookies</a>
          </li>
        </ul>
      </div>
      <div class="section">
        <p>Social</p>
        <ul>
          <li>
            <a href="#">Facebook</a>
          </li>
          <li>
            <a href="#">Linkedin</a>
          </li>
          <li>
            <a href="#">Twitter</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
</template>

<style scoped>
footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #1a1a1a;
  color: #fff;
}

footer div {
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

footer div .section {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

footer div .section p {
  font-size: 1.2rem;
  font-weight: 600;
}

footer div .section ul li a {
  color: #fff;
  text-decoration: none;
}

footer div .section ul li a:hover {
  color: #f5f5f5;
}

footer div .footerTitle {
  font-size: 2rem;
  font-weight: 600;
}

footer div .footerTitle a {
  color: #fff;
  text-decoration: none;
}

footer div .footerTitle a:hover {
  color: #f5f5f5;
}
</style>
