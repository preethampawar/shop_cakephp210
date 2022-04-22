For more information: https://reactjs.org/docs/add-react-to-a-website.html#add-react-in-one-minute

Add JSX to a Project
Adding JSX to a project doesn’t require complicated tools like a bundler or a development server. Essentially, adding JSX is a lot like adding a CSS preprocessor. The only requirement is to have Node.js installed on your computer.

Go to your project folder in the terminal, and paste these two commands:

Step 1: Run npm init -y (if it fails, here’s a fix)
Step 2: Run npm install babel-cli@6 babel-preset-react-app@3

*Tip
We’re using npm here only to install the JSX preprocessor; you won’t need it for anything else. Both React and the application code can stay as <script> tags with no changes.

Congratulations! You just added a production-ready JSX setup to your project.

Run JSX Preprocessor
Step 3: Create a folder called src and run this terminal command:
npx babel --watch src --out-dir . --presets react-app/prod

*Note
npx is not a typo — it’s a package runner tool that comes with npm 5.2+.

If you see an error message saying “You have mistakenly installed the babel package”, you might have missed the previous step. Perform it in the same folder, and then try again.

Don’t wait for it to finish — this command starts an automated watcher for JSX.

If you now create a file called src/like_button.js with this JSX starter code, the watcher will create a preprocessed like_button.js with the plain JavaScript code suitable for the browser. When you edit the source file with JSX, the transform will re-run automatically.

As a bonus, this also lets you use modern JavaScript syntax features like classes without worrying about breaking older browsers. The tool we just used is called Babel, and you can learn more about it from its documentation.

// configuring react-js in my-shop project
1. Create folder "react-myshop"
2. Run: npm init -y
3. Run: npm install babel-cli@6 babel-preset-react-app@3
4. Create folder "src" in react-app. (/react-app/src)
5. Run: npx babel --watch src --out-dir . --presets react-app/prod
    Note. Add JSX components in folder /react-app/src. The generated JS components will be available in /react-app/
6. Include react and reactDOM libraries in webpage (index.html) or layout (buyer.ctp)
    <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
    <!-- Load React components. These components are present in folder /react-app/  -->
    <script src="/react-app/like_button.js"></script>