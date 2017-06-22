import com.google.gson.Gson;
import com.squareup.okhttp.MediaType;
import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.RequestBody;
import processmaker.pmio.ApiClient;
import processmaker.pmio.ApiException;
import processmaker.pmio.api.Client;
import processmaker.pmio.model.*;

import java.io.FileInputStream;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;

public class OauthExample {

    private static final String PROPERTIES_FILE = "src/main/resources/env.properties";

    public static void main(String[] args) throws IOException, ApiException {

        Properties props = new Properties();
        props.load(new FileInputStream(PROPERTIES_FILE));

        Client client = new Client();

        /** Setting up host with base path and access token for API core */
        ApiClient apiClient = client.getApiClient();
        apiClient.setBasePath("https://"+props.getProperty("host")+"/api/v1");
        apiClient.setAccessToken(props.getProperty("accesToken"));

        /** Optionally you may enable logging */
        apiClient.setDebugging(true);



        /** Getting info about existing user that relative to token*/
        UserItem userItems = client.myselfUser(0, 1);
        System.out.println(userItems);

        /** Create users Bob and Alice*/

        UserCreateItem bobUserCreateItem = constructUserCreateItem("Bob" + Math.random(), "Bob123", "BobLast", "BobFirst", "bob@processmaker.com");
        UserCreateItem aliceUserCreateItem = constructUserCreateItem("Alice" + Math.random(), "Alice123", "AliceLast", "AliceFirst", "alice@processmaker.com");

        UserItem bobUserItem = client.addUser(bobUserCreateItem);
        System.out.println(bobUserItem);

        UserItem aliceUserItem = client.addUser(aliceUserCreateItem);
        System.out.println(aliceUserItem);


        /** Getting additional credentials to get access token for created users */

        OauthClientItem bobOauthClientItem = client.findOauthClientById(
            bobUserItem.getData().getId(),
            String.valueOf(bobUserItem.getData().getAttributes().getClients().get(0))
        );
        System.out.println(bobOauthClientItem);

        OauthClientItem aliceOauthClientItem = client.findOauthClientById(
            aliceUserItem.getData().getId(),
            String.valueOf(aliceUserItem.getData().getAttributes().getClients().get(0))
        );
        System.out.println(aliceOauthClientItem);

        /** Getting access token for created users Bob and Alice */
        OkHttpClient httpClient = client.getApiClient().getHttpClient();

        String bobOauthArguments = createOauthArguments(bobUserCreateItem, bobOauthClientItem);

        Request request = new Request.Builder()
            .post(RequestBody.create(MediaType.parse("application/json"), bobOauthArguments))
            .url("https://"+props.getProperty("host")+"/oauth/access_token")
            .build();
        String bobOauthToken = httpClient.newCall(request).execute().body().string();
        System.out.println(bobOauthToken);


        String aliceOauthArguments = createOauthArguments(aliceUserCreateItem, aliceOauthClientItem);

        request = new Request.Builder()
            .post(RequestBody.create(MediaType.parse("application/json"), aliceOauthArguments))
            .url("https://"+props.getProperty("host")+"/oauth/access_token")
            .build();
        String aliceOauthToken = httpClient.newCall(request).execute().body().string();
        System.out.println(aliceOauthToken);


    }

    private static String createOauthArguments(UserCreateItem bobUserCreateItem, OauthClientItem bobOauthClientItem) {
        Map<String, String> authArgs = new HashMap<String, String>();
        authArgs.put("grant_type", "password");
        authArgs.put("client_id", String.valueOf(bobOauthClientItem.getData().getId()));
        authArgs.put("client_secret", bobOauthClientItem.getData().getAttributes().getSecret());
        authArgs.put("username", bobUserCreateItem.getData().getAttributes().getUsername());
        authArgs.put("password", bobUserCreateItem.getData().getAttributes().getPassword());
        return new Gson().toJson(authArgs);
    }

    private static UserCreateItem constructUserCreateItem(String username, String password, String lastname, String firstname, String email) {
        UserAttributes userAttributes = new UserAttributes();
        userAttributes.setUsername(username);
        userAttributes.setPassword(password);
        userAttributes.setLastname(lastname);
        userAttributes.setFirstname(firstname);
        userAttributes.setEmail(email);

        User user = new User();
        user.setAttributes(userAttributes);
        UserCreateItem userCreateItem = new UserCreateItem();
        userCreateItem.setData(user);
        return userCreateItem;
    }
}
