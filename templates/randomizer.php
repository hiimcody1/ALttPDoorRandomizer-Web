<div class="container pt-5">
    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div class="h-100 p-5 bg-white border rounded-3">
          <h2>Generate a Preset</h2>
          <p>Generate a DR seed from a list of curated presets</p>
            <span data-bs-toggle="tooltip" data-bs-title="Not ready for use">
              <span class="dropdown">
                  <a class="btn btn-success dropdown-toggle disabled" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Generate
                  </a>

                  <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">True Pot Hunt</a></li>
                      <li><a class="dropdown-item" href="#">True Pot Hunt (With Door Shuffle)</a></li>
                  </ul>
              </span>
            </span>
            <span class="dropdown">
                <a class="btn btn-success dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Download JSON
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/randomizer/preset/tph">True Pot Hunt</a></li>
                    <li><a class="dropdown-item" href="/randomizer/preset/tph-dr">True Pot Hunt (With Door Shuffle)</a></li>
                </ul>
            </span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="h-100 p-5 bg-white border rounded-3">
          <h2>Use the Customizer</h2>
          <p>Create a custom DR seed by selecting from multiple options and settings</p>
          <a class="btn btn-warning" href="/customizer/start">Launch Web Customizer</a>
          <a href="https://github.com/aerinon/ALttPDoorRandomizer/releases/latest" target="_blank" class="btn btn-secondary my-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Download the latest DR program to your computer for more customization options">Download Local Program</a>
        </div>
      </div>
    </div>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
</div>