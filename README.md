# Full Stack Test

WPoets Full Stack Developer Test

Hi Full-stacker!

Great that you're interested in this exercise! Thanks a lot for making it. The exercise consits of an assignment. It is related to the WPoets working ways. Good luck and we are looking forward to hearing from you soon!

To complete these assignment you need to fork this repo. When you're done you can push your changes to your own repo (and let us know where to find it ofcourse).

<h2>Task</h2>
<ul>
  <li>Create a CRUD functionality using PHP, MySQL.</li>
	<li>Fetch the data to display the section that matches the given design using HTML5, CSS3, jQuery, Bootstrap.</li>
</ul>

<h2>Design</h2>

<h5>In Web view</h5>
<ul>
  <li>Column 1 is tabs. Each tab is a seperate slider.</li>
	<li>Clicking on the tab will change the slider in Column 2.</li>
	<li>
		Column 2 is a slider connected with column 3.
		<ul>
			<li>Which means when the slide in column 2 changes, the image in column 3 will change with it.</li>
			<li>Controls are attached to column 2 only.</li>
		</ul>
	</li>
	<li>Image in column 3 is a 1:1 image.</li>
</ul>

<h5>In Mobile view</h5>
<ul>
  <li>Column 1 changes to accordion.</li>
  <li>Column 2 is a slider with images from column 3 as background images.</li>
</ul>

<strong>Note: Please refer to the files directory for design files, relevant icons/images and styleguide.</strong>

<h2>Technical questions</h2>

Please answer the following questions in a markdown file called <code>Answers to technical questions.md</code>

<ul>
  <li>How long did you spend on the coding test? What would you add to your solution if you had more time? If you didn't spend much time on the coding test then use this as an opportunity to explain what you would add.</li>
	<li>How would you track down a performance issue in production? Have you ever had to do this?</li>
	<li>Please describe yourself using JSON.</li>
</ul>

<h2>Project Setup</h2>

<h5>1. Clone the Repository</h5>
<ul>
	<li>git clone https://github.com/karanpgajjar/wpoets-fullstack-test.git</li>
	<li>Move into the project directory:</li>
	<ul>
		<li>cd wpoets-fullstack-test</li>
	</ul>
</ul>

<h5>2. Create Database</h5>
<ul>
	<li>Create a new MySQL database.</li>
	<li>Example:</li>
	<ul>
		<li>CREATE DATABASE wpoets_test;</li>
	</ul>
</ul>

<h5>3. Import Database Schema</h5>
<ul>
	<li>Import the provided SQL file into the database:</li>
	<ul>
		<li>wpoets.sql</li>
	</ul>
	<li>You can import it using phpMyAdmin or MySQL command line.</li>
</ul>

<h5>4. Configure Database Connection</h5>
<ul>
	<li>Open the following file:</li>
	<ul>
		<li>config/db.php</li>
		<li>Update the database credentials:</li>
		<span>$host = "localhost";</span>
		<span>$username = "root";</span>
		<span>$password = "";</span>
		<span>$database = "wpoets_test";</span>
	</ul>
</ul>

<h5>5. Configure Base URL</h5>
<ul>
	<li>Open:</li>
	<ul>
		<li>config/config.php</li>
	</ul>
	<li>Update the base URL according to your local environment:</li>
	<ul>
		<li>define('BASE_URL', 'http://localhost/wpoets-fullstack-test/');</li>
	</ul>
</ul>

<h5>6. Run the Application</h5>
<ul>
	<li>Place the project inside your web server directory:</li>
	<ul>
		<li>XAMPP → htdocs</li>
		<li>WAMP → www</li>
		<li>MAMP → htdocs</li>
	</ul>
	<li>Start</li>
	<ul>
		<li>Apache</li>
		<li>MySQL</li>
	</ul>
	<li>Then open:</li>
	<ul>
		<li>http://localhost/wpoets-fullstack-test/</li>
	</ul>
</ul>

<h2>Requirements</h2>
<ul>
	<li>PHP 8.0+</li>
	<li>MySQL 5.7+ or MySQL 8+</li>
	<li>Apache Server</li>
	<li>XAMPP/WAMP/MAMP</li>
</ul>

<h2>Project Structure</h2>
<li>config/</li>
<ul>
	<li>config.php</li>
	<li>db.php</li>
</ul>
<li>assets/</li>
<li>controllers/</li>
<li>models/</li>
<li>wpoets.sql</li>

<h2>Notes</h2>
<ul>
	<li>Database schema is included in wpoets.sql.</li>
	<li>Update database credentials before running the project.</li>
	<li>Update the base URL if the project directory name is changed.</li>
	<li>No additional dependencies are required.</li>
</ul>