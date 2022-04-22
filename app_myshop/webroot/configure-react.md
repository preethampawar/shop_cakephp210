Configuring react-js in my-shop project. Make sure NodeJs is installed

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

For more information: https://reactjs.org/docs/add-react-to-a-website.html#add-react-in-one-minute